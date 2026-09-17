<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Lab;
use App\Models\SumberDana;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Halaman Backup Database.
     */
    public function indexDatabase()
    {
        $info = [
            'driver' => config('database.default'),
            'database' => config('database.connections.'.config('database.default').'.database'),
            'host' => config('database.connections.'.config('database.default').'.host'),
        ];

        $stats = [
            'users' => User::count(),
            'labs' => Lab::count(),
            'sumber_danas' => SumberDana::count(),
            'inventaris' => Inventaris::count(),
        ];

        $size = $this->getDatabaseSize();

        return view('admin.backup.database', compact('info', 'stats', 'size'));
    }

    /**
     * Halaman Export CSV.
     */
    public function indexCsv()
    {
        $stats = [
            'users' => User::count(),
            'labs' => Lab::count(),
            'sumber_danas' => SumberDana::count(),
            'inventaris' => Inventaris::count(),
        ];

        return view('admin.backup.csv', compact('stats'));
    }

    /**
     * Download backup SQL.
     */
    public function backupDatabase()
    {
        $filename = 'backup_inventaris_'.date('Ymd_His').'.sql';
        $driver = config('database.default');

        if ($driver !== 'mysql') {
            return back()->with('error', 'Backup database cuma support MySQL. Driver lu: '.$driver);
        }

        $tables = ['users', 'labs', 'sumber_danas', 'inventaris'];

        return response()->streamDownload(function () use ($tables) {
            $output = fopen('php://output', 'w');

            fwrite($output, "-- ============================================\n");
            fwrite($output, "-- Backup Database Inventaris TKJ\n");
            fwrite($output, '-- Tanggal: '.date('Y-m-d H:i:s')."\n");
            fwrite($output, '-- Database: '.config('database.connections.mysql.database')."\n");
            fwrite($output, "-- ============================================\n\n");

            fwrite($output, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($output, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n");

            foreach ($tables as $table) {
                $this->dumpTable($output, $table);
            }

            fwrite($output, "SET FOREIGN_KEY_CHECKS=1;\n");

            fclose($output);
        }, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function dumpTable($output, $table)
    {
        if (! DB::getSchemaBuilder()->hasTable($table)) {
            return;
        }

        fwrite($output, "-- ============================================\n");
        fwrite($output, "-- Table structure for `{$table}`\n");
        fwrite($output, "-- ============================================\n");

        $create = DB::select("SHOW CREATE TABLE `{$table}`");
        $createSql = $create[0]->{'Create Table'} ?? '';

        fwrite($output, "DROP TABLE IF EXISTS `{$table}`;\n");
        fwrite($output, $createSql.";\n\n");

        $rows = DB::table($table)->get();

        if ($rows->count() > 0) {
            fwrite($output, "-- Data for `{$table}`\n");

            foreach ($rows as $row) {
                $columns = array_keys((array) $row);
                $values = array_map(function ($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }

                    return "'".addslashes($value)."'";
                }, (array) $row);

                fwrite($output, "INSERT INTO `{$table}` (`".implode('`, `', $columns).'`) VALUES ('.implode(', ', $values).");\n");
            }

            fwrite($output, "\n");
        }
    }

    /**
     * Download CSV per tabel.
     */
    public function exportCsv($table)
    {
        $allowed = ['users', 'labs', 'sumber_danas', 'inventaris'];

        if (! in_array($table, $allowed)) {
            return back()->with('error', 'Tabel tidak diizinkan.');
        }

        $filename = $table.'_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($table) {
            $handle = fopen('php://output', 'w');

            // BOM biar Excel baca UTF-8 dengan bener
            fwrite($handle, "\xEF\xBB\xBF");

            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            fputcsv($handle, $columns);

            DB::table($table)->orderBy('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, (array) $row);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function getDatabaseSize()
    {
        try {
            $driver = config('database.default');

            if ($driver !== 'mysql') {
                return 'N/A (driver: '.$driver.')';
            }

            $dbName = config('database.connections.mysql.database');
            $result = DB::select('
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
            ', [$dbName]);

            $sizeMb = $result[0]->size_mb ?? 0;

            if ($sizeMb < 1) {
                return round($sizeMb * 1024, 2).' KB';
            }

            return $sizeMb.' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
