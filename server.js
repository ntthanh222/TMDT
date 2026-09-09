const express = require('express');
const cors = require('cors');
const db = require('./db');
require('dotenv').config();

const app = express();

// Cho phép CORS truy cập từ Web, Tên miền ngoài & App điện thoại
app.use(cors({
    origin: '*',
    methods: ['GET', 'POST', 'PUT', 'DELETE'],
    allowedHeaders: ['Content-Type', 'Authorization']
}));

app.use(express.json());

// 1. Kiểm tra trạng thái Server
app.get('/', (req, res) => {
    res.send('Server Coffee Shop dang hoat dong binh thuong!');
});

// 2. API Áp dụng mã giảm giá
app.post('/api/apply-coupon', async (req, res) => {
    const { code, totalAmount } = req.body;

    if (!code || !totalAmount) {
        return res.status(400).json({ success: false, message: 'Thiếu thông tin mã hoặc tổng tiền!' });
    }

    try {
        const [coupons] = await db.query('SELECT * FROM coupons WHERE code = ?', [code]);
        
        if (coupons.length === 0) {
            return res.status(400).json({ success: false, message: 'Mã giảm giá không tồn tại!' });
        }

        const coupon = coupons[0];

        if (totalAmount < coupon.min_order_value) {
            return res.status(400).json({ 
                success: false, 
                message: `Đơn hàng tối thiểu phải từ ${Number(coupon.min_order_value).toLocaleString()} VNĐ để dùng mã này!` 
            });
        }

        let discountAmount = coupon.discount_type === 'percent' 
            ? (totalAmount * coupon.discount_value) / 100 
            : Number(coupon.discount_value);

        res.json({ 
            success: true, 
            message: 'Áp dụng mã giảm giá thành công!',
            code: coupon.code,
            discountAmount 
        });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Lỗi máy chủ khi xử lý mã giảm giá' });
    }
});

// 3. API Đặt hàng & Thanh toán (Transaction)
app.post('/api/checkout', async (req, res) => {
    const { userId, customerName, phone, address, cartItems, discountAmount = 0, paymentMethod } = req.body;

    if (!customerName || !phone || !address || !cartItems || cartItems.length === 0) {
        return res.status(400).json({ success: false, message: 'Vui lòng điền đầy đủ thông tin giao hàng và giỏ hàng!' });
    }

    const shippingFee = 30000;
    const connection = await db.getConnection();

    try {
        await connection.beginTransaction();

        let totalAmount = 0;

        for (const item of cartItems) {
            const [products] = await connection.query(
                'SELECT price, stock_quantity, name FROM products WHERE id = ?', 
                [item.productId]
            );

            if (products.length === 0) {
                throw new Error(`Sản phẩm (ID: ${item.productId}) không tồn tại!`);
            }

            const product = products[0];

            if (product.stock_quantity < item.quantity) {
                throw new Error(`Sản phẩm "${product.name}" chỉ còn ${product.stock_quantity} item trong kho!`);
            }

            totalAmount += product.price * item.quantity;
        }

        const finalAmount = totalAmount - discountAmount + shippingFee;

        const [orderResult] = await connection.query(
            `INSERT INTO orders (user_id, customer_name, phone, address, total_amount, discount_amount, shipping_fee, final_amount, payment_method) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [userId || null, customerName, phone, address, totalAmount, discountAmount, shippingFee, finalAmount, paymentMethod || 'COD']
        );

        const orderId = orderResult.insertId;

        for (const item of cartItems) {
            const [products] = await connection.query('SELECT price FROM products WHERE id = ?', [item.productId]);
            
            await connection.query(
                `INSERT INTO order_details (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)`,
                [orderId, item.productId, products[0].price, item.quantity]
            );

            await connection.query(
                `UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?`,
                [item.quantity, item.productId]
            );
        }

        await connection.commit();

        res.json({ 
            success: true, 
            message: 'Đặt hàng thành công!', 
            orderId: orderId,
            finalAmount: finalAmount 
        });

    } catch (error) {
        await connection.rollback();
        res.status(400).json({ success: false, message: error.message });
    } finally {
        connection.release();
    }
});

// 4. API Lấy thông tin & Trạng thái đơn hàng
app.get('/api/orders/:id', async (req, res) => {
    const { id } = req.params;
    try {
        const [orders] = await db.query('SELECT * FROM orders WHERE id = ?', [id]);
        if (orders.length === 0) {
            return res.status(404).json({ success: false, message: 'Không tìm thấy đơn hàng!' });
        }
        
        const [details] = await db.query(
            `SELECT od.*, p.name FROM order_details od 
             JOIN products p ON od.product_id = p.id 
             WHERE od.order_id = ?`, [id]
        );

        res.json({
            success: true,
            order: orders[0],
            items: details
        });
    } catch (error) {
        res.status(500).json({ success: false, message: 'Lỗi máy chủ khi lấy đơn hàng' });
    }
});

// 5. Khởi chạy Server lắng nghe tất cả các IP
const PORT = process.env.PORT || 5000;
// ... [Các đoạn code cũ của bạn ở phía trên] ...


// =========================================================
// THÊM ĐOẠN CODE NÀY VÀO TRƯỚC DÒNG APP.LISTEN
// =========================================================
app.get('/api/products', async (req, res) => {
    try {
        const [products] = await db.query(`
            SELECT id, name, price, stock_quantity, image 
            FROM products 
            WHERE is_active = true
        `);
        res.json(products);
    } catch (error) {
        console.error("Lỗi lấy sản phẩm:", error);
        res.status(500).json({ success: false, message: 'Lỗi khi lấy danh sách sản phẩm' });
    }
});

app.listen(PORT, '0.0.0.0', () => {
    console.log(`Server running on port ${PORT}`);
});