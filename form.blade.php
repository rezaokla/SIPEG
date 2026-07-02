<label>Nama Program</label>
<input type="text" name="nama" value="{{ old('nama', $program->nama ?? '') }}" required>
<label>Keterangan</label>
<textarea name="keterangan" rows="4" required>{{ old('keterangan', $program->keterangan ?? '') }}</textarea>
<div class="grid two-col">
    <div>
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $program->tanggal_mulai ?? '') }}" required>
    </div>
    <div>
        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $program->tanggal_selesai ?? '') }}" required>
    </div>
    <div>
        <label>Tahun</label>
        <input type="number" name="tahun" value="{{ old('tahun', $program->tahun ?? date('Y')) }}" required>
    </div>
    <div>
        <label>Anggaran</label>
        <input type="number" name="anggaran" min="0" value="{{ old('anggaran', $program->anggaran ?? 0) }}" required>
    </div>
</div>
<label>Penanggung Jawab</label>
<select name="penanggung_jawab_id">
    <option value="">Pilih penanggung jawab</option>
    @foreach($pegawaiList as $pegawai)
        <option value="{{ $pegawai->id }}" @selected(old('penanggung_jawab_id', $program->penanggung_jawab_id ?? '') == $pegawai->id)>{{ $pegawai->nama }}</option>
    @endforeach
</select>
