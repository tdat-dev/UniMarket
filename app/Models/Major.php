<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    public $timestamps = false; // Bảng majors trong SQL của bạn không có created_at/updated_at
    protected $fillable = ['name', 'code'];
}