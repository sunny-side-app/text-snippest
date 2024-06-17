<?php

namespace Database\Seeds;

require_once "Database/AbstractSeeder.php";
require_once "vendor/autoload.php";

use Database\AbstractSeeder;
use Faker\Factory as Faker;

class TextSnippetsSeeder extends AbstractSeeder {
    protected ?string $tableName = 'snippets';
    protected array $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'snippet_name'
        ],
        [
            'data_type' => 'text',
            'column_name' => 'snippet'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'validity_period'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'programming_language'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'url'
        ]
        // created_at と updated_at は、マイグレーションで定義されているように、MySQL の CURRENT_TIMESTAMP を使用し、シーディング時には必要ありません。
        // [
        //     'data_type' => 'datetime',
        //     'column_name' => 'created_at'
        // ],
        // [
        //     'data_type' => 'datetime',
        //     'column_name' => 'updated_at'
        // ]
    ];

    public function createRowData(int $numberOfRows = 1000): array {
        $faker = Faker::create();
        $data = [];

        $validityPeriods = ['10_minutes', '1_hour', '1_day', 'permanent'];
        $programmingLanguages = ['Python', 'JavaScript', 'Java', 'C#', 'PHP', 'Ruby', 'Go', 'Swift', 'Kotlin', 'Rust'];

        for ($i = 0; $i < $numberOfRows; $i++) {
            $data[] = [
                $faker->sentence(3), // snippet_name
                $faker->text(200), // snippet
                $faker->randomElement($validityPeriods), // validity_period
                $faker->randomElement($programmingLanguages), // programming_language
                $faker->url // url
                // $faker->dateTimeThisYear->format('Y-m-d H:i:s'), // created_at
                // $faker->dateTimeThisYear->format('Y-m-d H:i:s') // updated_at
            ];
        }

        return $data;
    }
}
