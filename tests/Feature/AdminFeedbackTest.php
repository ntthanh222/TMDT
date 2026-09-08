<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeedbackTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);
    }

    public function test_guest_cannot_access_admin_feedbacks(): void
    {
        $response = $this->get(route('admin.feedbacks.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_feedbacks(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('admin.feedbacks.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_feedbacks_list(): void
    {
        Feedback::create([
            'name' => 'Lê Khách Hàng',
            'email' => 'lekhachhang@example.com',
            'phone' => '0987654321',
            'subject' => 'Đóng góp ý kiến mở rộng menu',
            'message' => 'Nên thêm các món bánh ngọt đi kèm cà phê.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.feedbacks.index'));

        $response->assertOk()
            ->assertSee('Quản Lý Phản Hồi', false)
            ->assertSee('Lê Khách Hàng')
            ->assertSee('Đóng góp ý kiến mở rộng menu');
    }

    public function test_admin_can_view_feedback_detail(): void
    {
        $feedback = Feedback::create([
            'name' => 'Ngô Khách Hàng',
            'email' => 'ngokhachhang@example.com',
            'phone' => '0911223344',
            'subject' => 'Hỗ trợ đổi địa chỉ',
            'message' => 'Tôi muốn đổi địa chỉ giao hàng của đơn vừa đặt.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.feedbacks.show', $feedback));

        $response->assertOk()
            ->assertSee("Chi tiết phản hồi #{$feedback->id}")
            ->assertSee('Tôi muốn đổi địa chỉ giao hàng của đơn vừa đặt.')
            ->assertSee('ngokhachhang@example.com');
    }

    public function test_admin_can_mark_feedback_as_read(): void
    {
        $feedback = Feedback::create([
            'name' => 'Đỗ Khách Hàng',
            'email' => 'dokhachhang@example.com',
            'message' => 'Tin nhắn phản hồi',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.feedbacks.read', $feedback));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $feedback->refresh();
        $this->assertTrue($feedback->is_read);
    }

    public function test_admin_can_mark_feedback_as_unread(): void
    {
        $feedback = Feedback::create([
            'name' => 'Phạm Khách Hàng',
            'email' => 'phamkhachhang@example.com',
            'message' => 'Tin nhắn đã đọc trước đó',
            'is_read' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.feedbacks.unread', $feedback));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $feedback->refresh();
        $this->assertFalse($feedback->is_read);
    }

    public function test_admin_can_delete_feedback(): void
    {
        $feedback = Feedback::create([
            'name' => 'Spam Bot',
            'email' => 'spam@bot.com',
            'message' => 'Nội dung rác quảng cáo',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.feedbacks.destroy', $feedback));

        $response->assertRedirect(route('admin.feedbacks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('feedbacks', ['id' => $feedback->id]);
    }
}
