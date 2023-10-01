<?php

namespace App\Enums;

enum StatusJobEnum: string
 {
    case Pending = 'pending';
    case Selection = 'selection';
    case Review = 'review';
    case Interview = 'interview';
    case OnBoarded = 'on boarded';
    case Declined = 'declained';
    case Approved = 'approved';
}