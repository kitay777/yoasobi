<?php


    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class RewardRequest extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'amount',
            'bank_name',
            'bank_account',
            'note',
            'status',
            'requested_at',
            'approved_at',
        ];

        protected $dates = [
            'requested_at',
            'approved_at',
            'created_at',
            'updated_at',
        ];

        /**
         * ユーザーとのリレーション
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
    ?>


