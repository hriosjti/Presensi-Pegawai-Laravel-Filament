    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateAbsensiPegawaiTable extends Migration
    {
        public function up(): void
        {
            Schema::create('absensi_pegawai', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
                $table->date('tanggal');
                $table->dateTime('waktu_checkin')->nullable();
                $table->dateTime('waktu_checkout')->nullable();
                $table->decimal('lat_checkin', 10, 8)->nullable();
                $table->decimal('long_checkin', 11, 8)->nullable();
                $table->decimal('lat_checkout', 10, 8)->nullable();
                $table->decimal('long_checkout', 11, 8)->nullable();
                $table->enum('status', ['hadir', 'terlambat', 'tanpa_keterangan', 'cuti'])->default('tanpa_keterangan');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('absensi_pegawai');
        }
    }
