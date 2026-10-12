<?php

namespace Database\Seeders;

use App\Models\Couple;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\Guest;
use App\Models\GuestMessage;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\Rsvp;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@wedding.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Templates
        $rustic = Template::firstOrCreate(
            ['slug' => 'rustic-bloom'],
            [
                'name' => 'Rustic Bloom',
                'category' => 'Rustic',
                'price' => 150000,
                'is_active' => true,
            ]
        );

        $modern = Template::firstOrCreate(
            ['slug' => 'modern-minimalist'],
            [
                'name' => 'Modern Minimalist',
                'category' => 'Modern',
                'price' => 175000,
                'is_active' => true,
            ]
        );

        $floral = Template::firstOrCreate(
            ['slug' => 'floral-elegance'],
            [
                'name' => 'Floral Elegance',
                'category' => 'Floral',
                'price' => 200000,
                'is_active' => true,
            ]
        );

        // 3. Sample Orders
        $order1 = Order::firstOrCreate(
            ['order_number' => 'ORD-982143'],
            [
                'template_id' => $rustic->id,
                'customer_name' => 'Budi Santoso',
                'customer_address' => 'Jl. Mawar No. 12, Jakarta Selatan',
                'wedding_date' => '2026-11-18',
                'customer_phone' => '081234567890',
                'total_price' => 150000,
                'payment_status' => 'paid',
                'order_status' => 'in_progress',
                'notes' => 'Tolong nuansa warna earthy warm terracotta.',
            ]
        );

        Order::firstOrCreate(
            ['order_number' => 'ORD-982142'],
            [
                'template_id' => $floral->id,
                'customer_name' => 'Rian Hidayat',
                'customer_address' => 'Bandung, Jawa Barat',
                'wedding_date' => '2026-12-25',
                'customer_phone' => '085799123344',
                'total_price' => 200000,
                'payment_status' => 'paid',
                'order_status' => 'completed',
            ]
        );

        Order::firstOrCreate(
            ['order_number' => 'ORD-982141'],
            [
                'template_id' => $modern->id,
                'customer_name' => 'Dimas Pratama',
                'customer_address' => 'Surabaya, Jawa Timur',
                'wedding_date' => '2027-01-12',
                'customer_phone' => '087811228899',
                'total_price' => 175000,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ]
        );

        // 4. Sample Invitation for ORD-982143
        $invitation = Invitation::firstOrCreate(
            ['order_id' => $order1->id],
            [
                'template_id' => $rustic->id,
                'slug' => 'andi-sinta',
                'publication_status' => 'published',
                'published_at' => now(),
            ]
        );

        // 5. Couple Profile
        Couple::firstOrCreate(
            ['invitation_id' => $invitation->id],
            [
                'partner_one_name' => 'Andi Pratama, S.Kom',
                'partner_one_father' => 'Bambang Pratama',
                'partner_one_mother' => 'Endang Sulastri',
                'partner_one_photo' => null,
                'partner_two_name' => 'Sinta Lestari, S.E',
                'partner_two_father' => 'Joko Lestari',
                'partner_two_mother' => 'Sri Wahyuni',
                'partner_two_photo' => null,
                'story' => 'Pertama kali bertemu di bangku kuliah pada tahun 2020. Setelah 6 tahun menjalin cerita penuh tawa dan impian bersama, kini kami mantap melangkah ke jenjang pernikahan yang suci.',
            ]
        );

        // 6. Events
        Event::firstOrCreate(
            ['invitation_id' => $invitation->id, 'event_type' => 'Akad Nikah'],
            [
                'start_at' => '2026-11-18 08:00:00',
                'end_at' => '2026-11-18 10:00:00',
                'venue_name' => 'Masjid Raya Al-Ikhlas',
                'address' => 'Jl. Protokol Utama No. 88, Jakarta Selatan',
                'maps_url' => 'https://maps.google.com',
                'sort_order' => 1,
            ]
        );

        Event::firstOrCreate(
            ['invitation_id' => $invitation->id, 'event_type' => 'Resepsi Pernikahan'],
            [
                'start_at' => '2026-11-18 11:00:00',
                'end_at' => '2026-11-18 14:00:00',
                'venue_name' => 'Grand Ballroom Sasana Kriya',
                'address' => 'Taman Mini Indonesia Indah, Jakarta Timur',
                'maps_url' => 'https://maps.google.com',
                'sort_order' => 2,
            ]
        );

        // 7. Gallery Photos
        GalleryPhoto::firstOrCreate(
            ['invitation_id' => $invitation->id, 'photo_path' => 'gallery/prewed-1.jpg'],
            ['caption' => 'Senja di Bromo', 'sort_order' => 1]
        );

        GalleryPhoto::firstOrCreate(
            ['invitation_id' => $invitation->id, 'photo_path' => 'gallery/prewed-2.jpg'],
            ['caption' => 'Langkah Menuju Hari Bahagia', 'sort_order' => 2]
        );

        // 8. Guests
        $guest1 = Guest::firstOrCreate(
            ['invitation_id' => $invitation->id, 'name' => 'Budi Santoso'],
            ['phone' => '081234567890', 'invitation_sent_at' => now()]
        );

        $guest2 = Guest::firstOrCreate(
            ['invitation_id' => $invitation->id, 'name' => 'Siti Rahmawati'],
            ['phone' => '081398765432', 'invitation_sent_at' => now()]
        );

        Guest::firstOrCreate(
            ['invitation_id' => $invitation->id, 'name' => 'Ahmad Fauzi & Partner'],
            ['phone' => '085612345678']
        );

        // 9. RSVP
        Rsvp::firstOrCreate(
            ['invitation_id' => $invitation->id, 'guest_name' => 'Budi Santoso'],
            [
                'guest_id' => $guest1->id,
                'attendance_status' => 'attending',
                'guest_count' => 2,
                'note' => 'Selamat untuk Andi & Sinta! InsyaAllah kami hadir.',
            ]
        );

        // 10. Guest Messages
        GuestMessage::firstOrCreate(
            ['invitation_id' => $invitation->id, 'guest_name' => 'Budi Santoso'],
            [
                'guest_id' => $guest1->id,
                'message' => 'Barakallahu lakuma wa baraka alaikuma wa jama\'a bainakuma fii khoir. Semoga sakinah mawaddah warahmah!',
                'is_approved' => true,
            ]
        );

        GuestMessage::firstOrCreate(
            ['invitation_id' => $invitation->id, 'guest_name' => 'Siti Rahmawati'],
            [
                'guest_id' => $guest2->id,
                'message' => 'Selamat menempuh hidup baru sahabatku Sinta! Bahagia selalu selamanya ❤️',
                'is_approved' => true,
            ]
        );
    }
}
