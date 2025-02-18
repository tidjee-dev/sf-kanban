<?php

namespace App\Service;

class IdGenerator
{
  /**
   * Generate a custom ID with a prefix (3 characters), current timestamp,
   * and a random 4-digit number.
   *
   * @param string $prefix The prefix for the ID
   * @return string The generated ID
   */
  public function generateId(string $prefix): string
  {
    if (!in_array($prefix, [
      'USR',
      'BRD',
      'LST',
      'TSK'
    ])) {
      throw new \InvalidArgumentException('Invalid prefix.');
    }

    $timestamp = time();
    $randomNumber = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    return sprintf('%s-%d-%s', $prefix, $timestamp, $randomNumber);
  }
}