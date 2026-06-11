<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\StorageImage;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events' ;

    protected $fillable = [
        'organization_id', 'event_category_id', 'event_type_id',
        'title', 'description', 'image', 'location',
        'quota', 'start_date', 'end_date',
        'type', 'price',
    ];

    protected $appends = ['image_url', 'has_stored_image'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'price'      => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    // ===== HELPERS =====

    public function hasStoredImage(): bool
    {
        return StorageImage::exists($this->image);
    }

    public function getHasStoredImageAttribute(): bool
    {
        return $this->hasStoredImage();
    }

    public function getImageUrlAttribute(): ?string
    {
        return StorageImage::url($this->image);
    }

    public function isFree(): bool
    {
        return $this->type === 'free';
    }

    // Hitung peserta yang sudah approved
    public function getApprovedCountAttribute(): int
    {
        return $this->registrations()
                    ->where('status', 'approved')
                    ->count();
    }

    // Hitung sisa kuota
    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->quota - $this->approved_count);
    }

    public function registrationDeadline(): ?\Carbon\Carbon
    {
        return $this->start_date?->copy()->startOfDay()->subDays(3);
    }

    public function isWithinRegistrationPeriod(): bool
    {
        $deadline = $this->registrationDeadline();

        return $deadline !== null && now()->lt($deadline);
    }

    // Cek apakah masih bisa daftar (H-3 + kuota)
    public function isOpen(): bool
    {
        return $this->isWithinRegistrationPeriod()
            && $this->remaining_quota > 0;
    }

    public function registrationClosedReason(): string
    {
        if ($this->remaining_quota <= 0) {
            return 'Kuota sudah penuh atau pendaftaran ditutup.';
        }

        return 'Pendaftaran ditutup 3 hari sebelum acara dimulai.';
    }

    // Scope filter berdasarkan kategori
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('event_category_id', $categoryId);
    }

    // Scope filter berdasarkan tipe (free/paid)
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope filter berdasarkan execution type (Online/Onsite)
    public function scopeByExecutionType($query, $typeId)
    {
        return $query->where('event_type_id', $typeId);
    }
}
