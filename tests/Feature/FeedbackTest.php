<?php

namespace Tests\Feature;

use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_contact_page(): void
    {
        $response = $this->get(route('contact.create'));

        $response->assertOk()
            ->assertSee('Liên Hệ', false)
            ->assertSee('Gửi liên hệ', false);
    }

    public function test_guest_can_submit_feedback(): void
    {
        $payload = [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0912345678',
            'subject' => 'Hỏi về nguồn gốc hạt cà phê Robusta',
            'message' => 'Cho mình hỏi hạt cà phê bên shop được thu hoạch từ nông trại nào vậy ạ?',
        ];

        $response = $this->post(route('contact.store'), $payload);

        $response->assertRedirect(route('contact.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('feedbacks', [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0912345678',
            'subject' => 'Hỏi về nguồn gốc hạt cà phê Robusta',
            'is_read' => false,
        ]);
    }

    public function test_name_is_required(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'valid@example.com',
            'message' => 'Nội dung phản hồi',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_email_is_required(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn B',
            'email' => '',
            'message' => 'Nội dung phản hồi',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn B',
            'email' => 'not-an-email',
            'message' => 'Nội dung phản hồi',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_message_is_required(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn B',
            'email' => 'valid@example.com',
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_message_too_long_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn B',
            'email' => 'valid@example.com',
            'message' => str_repeat('b', 3001),
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_feedback_persists_in_database(): void
    {
        $payload = [
            'name' => 'Trần Thị C',
            'email' => 'tranthic@example.com',
            'message' => 'Dịch vụ của quán rất chu đáo!',
        ];

        $this->post(route('contact.store'), $payload);

        $this->assertDatabaseCount('feedbacks', 1);
        $feedback = Feedback::first();
        $this->assertSame('Trần Thị C', $feedback->name);
        $this->assertSame('tranthic@example.com', $feedback->email);
        $this->assertSame('Dịch vụ của quán rất chu đáo!', $feedback->message);
    }

    public function test_is_read_is_initially_false(): void
    {
        $payload = [
            'name' => 'Lê Văn D',
            'email' => 'levand@example.com',
            'message' => 'Kiểm tra trạng thái is_read ban đầu',
        ];

        $this->post(route('contact.store'), $payload);

        $feedback = Feedback::first();
        $this->assertNotNull($feedback);
        $this->assertFalse($feedback->is_read);
    }
}
