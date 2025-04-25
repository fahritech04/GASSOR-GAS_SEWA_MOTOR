<?php

namespace App\Interfaces;

interface TransactionRepositoryInterface
{
    public function getTransactionDataFormSession();
    public function saveTransactionDataToSession($data);
    public function saveTransaction($data);
    public function getTransactionByCode($code);
    public function getTransactionByCodeEmailPhone($code, $email, $phone);
}
