<?php

use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->command('fetch:api-data stocks --accountId=1 --limit=500')
    ->twiceDailyAt(9, 21) // Два раза в день
    ->timezone('UTC+3')   // Московское время
    ->description('Fetch stocks data');

app(Schedule::class)->command('fetch:api-data incomes --accountId=1 --limit=500')
    ->twiceDailyAt(9, 21) // Два раза в день
    ->timezone('UTC+3')   // Московское время
    ->description('Fetch incomes data');

app(Schedule::class)->command('fetch:api-data orders --accountId=1 --limit=500')
    ->twiceDailyAt(9, 21) // Два раза в день
    ->timezone('UTC+3')   // Московское время
    ->description('Fetch orders data');

app(Schedule::class)->command('fetch:api-data sales --accountId=1 --limit=500')
    ->twiceDailyAt(9, 21) // Два раза в день
    ->timezone('UTC+3')   // Московское время
    ->description('Fetch sales data');

