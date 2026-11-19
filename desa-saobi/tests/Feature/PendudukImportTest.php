<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class PendudukImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_excel_template(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.penduduk.template'));

        $response->assertOk();
        $this->assertStringContainsString('spreadsheetml', (string) $response->headers->get('Content-Type'));
    }

    public function test_import_creates_residents_without_login_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray([
            ['NIK', 'No KK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'Dusun', 'RT', 'RW', 'Agama', 'Pekerjaan', 'Status Perkawinan', 'Telepon'],
            ['3529012345678888', '3529012345670000', 'Warga Baru Import', 'Sumenep', '1995-04-12', 'Laki-laki', 'Dusun Saobi', 'Saobi', '01', '01', 'Islam', 'Nelayan', 'Kawin', '081200000000'],
        ]);

        $path = tempnam(sys_get_temp_dir(), 'imp') . '.xlsx';
        (new Xlsx($spreadsheet))->save($path);

        $file = new UploadedFile(
            $path,
            'data_penduduk.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $this->actingAs($admin)
            ->post(route('admin.penduduk.import'), ['file_excel' => $file])
            ->assertRedirect(route('admin.penduduk.index'));

        $this->assertDatabaseHas('penduduks', [
            'nik' => '3529012345678888',
            'nama_lengkap' => 'Warga Baru Import',
        ]);

        // Akun login TIDAK dibuat otomatis saat import.
        $this->assertDatabaseMissing('users', ['nik' => '3529012345678888']);

        @unlink($path);
    }
}
