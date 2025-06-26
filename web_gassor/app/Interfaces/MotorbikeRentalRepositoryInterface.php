<?php

namespace App\Interfaces;

interface MotorbikeRentalRepositoryInterface
{
    public function getAllMotorbikeRentals($search = null, $city = null, $category = null);

    public function getAllMotorcycles($search = null, $city = null, $category = null);

    public function getPopularMotorbikeRentals($limit = 5);

    public function getMotorbikeRentalByCitySlug($slug);

    public function getMotorbikeRentalByCategorySlug($slug);

    public function getMotorbikeRentalBySlug($slug);

    public function getMotorbikeRentalMotorcycleById($id);
}
