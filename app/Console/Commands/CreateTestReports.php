<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use Carbon\Carbon;

class CreateTestReports extends Command
{
    protected $signature = 'reports:create-test';
    protected $description = 'Create test reports with specific statuses';

    public function handle()
    {
        $this->info('Creating test reports...');

        $testData = [
            // Abmahnungen von vor 3 Wochen
            [
                'status' => 6,
                'count' => 5,
                'date' => Carbon::now()->subWeeks(3),
                'description' => 'Abmahnung von vor 3 Wochen'
            ],
            // Mahnungen von vor 4 Wochen
            [
                'status' => 14,
                'count' => 3,
                'date' => Carbon::now()->subWeeks(4),
                'description' => 'Mahnung von vor 4 Wochen'
            ],
            // Erfolgreiche Abmahnungen ohne UE
            [
                'status' => 12,
                'count' => 4,
                'date' => Carbon::now()->subWeeks(2),
                'description' => 'Erfolgreiche Abmahnung ohne UE'
            ],
        ];

        $kennzeichen = [
            ['HH', 'AB', '123'],
            ['B', 'CD', '456'],
            ['M', 'EF', '789'],
            ['K', 'GH', '012'],
            ['F', 'IJ', '345'],
        ];

        foreach ($testData as $data) {
            $this->info("\nCreating {$data['description']}...");
            
            for ($i = 0; $i < $data['count']; $i++) {
                $k = $kennzeichen[$i];
                
                $report = new Report();
                $report->plateCode1 = $k[0];
                $report->plateCode2 = $k[1];
                $report->plateCode3 = $k[2];
                $report->companyname = 'Test Firma GmbH';
                $report->firstname = 'Max';
                $report->lastname = 'Mustermann';
                $report->email = 'test@example.com';
                $report->street = 'Teststraße 123';
                $report->zip = '12345';
                $report->city = 'Teststadt';
                $report->country = 'Deutschland';
                $report->status = $data['status'];
                $report->createdAt = $data['date'];
                $report->status_changed_at = $data['date'];
                $report->date = $data['date'];
                $report->lawyerapprovalstatus = 0;
                $report->uploadStatus = 0;
                $report->paymentstatus = false;
                $report->adminemailsent = false;
                $report->paidkba = false;
                $report->lat = 0;
                $report->lng = 0;
                $report->sentStatus = 0;
                $report->order = $i + 1;
                
                // Spezifische Daten je nach Status
                if ($data['status'] === 12) {
                    // Erfolgreiche Abmahnung ohne UE
                    $report->halterdatum = Carbon::now()->subWeeks(4);
                }
                
                $report->save();
                
                $this->info("Created {$data['description']} with kennzeichen {$k[0]}-{$k[1]}-{$k[2]}");
            }
        }

        $this->info("\nDone creating test reports!");
    }
}
