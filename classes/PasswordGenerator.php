<?php

class PasswordGenerator
{
    private string $lower = "abcdefghijklmnopqrstuvwxyz";
    private string $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private string $numbers = "0123456789";
    private string $special = "!@#$%^&*()-_=+[]{};:,.<>?/";

    public function generate(
        int $length,
        int $lowerCount,
        int $upperCount,
        int $numberCount,
        int $specialCount
    ): string
    {
        $password = [];

        $password = array_merge(
            $password,
            $this->pickRandom($this->lower, $lowerCount),
            $this->pickRandom($this->upper, $upperCount),
            $this->pickRandom($this->numbers, $numberCount),
            $this->pickRandom($this->special, $specialCount)
        );

        shuffle($password);

        return implode("", $password);
    }

    private function pickRandom(string $pool, int $count): array
    {
        $result = [];
        $max = strlen($pool) - 1;

        for ($i = 0; $i < $count; $i++)
        {
            $result[] = $pool[random_int(0, $max)];
        }

        return $result;
    }
}