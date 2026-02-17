<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing users table (don't create new one)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('password');
                }
                if (!Schema::hasColumn('users', 'subscription_tier')) {
                    $table->enum('subscription_tier', ['free', 'premium', 'business'])->default('free')->after('avatar');
                }
                if (!Schema::hasColumn('users', 'premium_expires_at')) {
                    $table->timestamp('premium_expires_at')->nullable()->after('subscription_tier');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('premium_expires_at');
                }
            });
        } else {
            // If users table doesn't exist, create it
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('avatar')->nullable();
                $table->enum('subscription_tier', ['free', 'premium', 'business'])->default('free');
                $table->timestamp('premium_expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Subscriptions table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stripe_subscription_id')->nullable();
            $table->enum('tier', ['premium', 'business']);
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->decimal('price', 10, 2);
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });

        // Premium Templates table
        Schema::create('premium_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->enum('required_tier', ['premium', 'business']);
            $table->boolean('is_exclusive')->default(false);
            $table->timestamps();
        });

        // User Downloads (for watermark-free tracking)
        Schema::create('user_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->boolean('watermark_removed')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // Support Tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ticket_number')->unique();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->string('subject');
            $table->text('message');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        // Support Ticket Replies
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('message');
            $table->boolean('is_internal_note')->default(false);
            $table->timestamps();
        });

        // Add premium flags to templates table
        Schema::table('templates', function (Blueprint $table) {
            if (!Schema::hasColumn('templates', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('templates', 'premium_tier')) {
                $table->enum('premium_tier', ['free', 'premium', 'business'])->default('free')->after('is_premium');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('user_downloads');
        Schema::dropIfExists('premium_templates');
        Schema::dropIfExists('subscriptions');
        
        // Don't drop users table, just remove columns we added
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'avatar')) {
                    $table->dropColumn('avatar');
                }
                if (Schema::hasColumn('users', 'subscription_tier')) {
                    $table->dropColumn('subscription_tier');
                }
                if (Schema::hasColumn('users', 'premium_expires_at')) {
                    $table->dropColumn('premium_expires_at');
                }
                if (Schema::hasColumn('users', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
        
        // Remove premium columns from templates
        Schema::table('templates', function (Blueprint $table) {
            if (Schema::hasColumn('templates', 'is_premium')) {
                $table->dropColumn('is_premium');
            }
            if (Schema::hasColumn('templates', 'premium_tier')) {
                $table->dropColumn('premium_tier');
            }
        });
    }
};