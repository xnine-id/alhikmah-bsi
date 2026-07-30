<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan kesalahan default yang digunakan oleh
    | kelas validator. Beberapa aturan ini memiliki beberapa versi seperti
    | aturan ukuran. Silakan modifikasi setiap pesan ini sesuai kebutuhan.
    |
    */

    'accepted' => ':Attribute harus diterima.',
    'accepted_if' => ':Attribute harus diterima ketika :other adalah :value.',
    'active_url' => ':Attribute harus berupa URL yang valid.',
    'after' => ':Attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':Attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':Attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':Attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':Attribute hanya boleh berisi huruf dan angka.',
    'array' => ':Attribute harus berupa array.',
    'ascii' => ':Attribute hanya boleh berisi karakter alfanumerik satu byte dan simbol.',
    'before' => ':Attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':Attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':Attribute harus memiliki antara :min dan :max item.',
        'file' => ':Attribute harus memiliki ukuran antara :min dan :max kilobyte.',
        'numeric' => ':Attribute harus memiliki nilai antara :min dan :max.',
        'string' => ':Attribute harus memiliki panjang antara :min dan :max karakter.',
    ],
    'boolean' => ':Attribute harus berupa true atau false.',
    'can' => ':Attribute berisi nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'contains' => ':Attribute tidak mengandung nilai yang diperlukan.',
    'current_password' => 'Kata sandi tidak benar.',
    'date' => ':Attribute harus berupa tanggal yang valid.',
    'date_equals' => ':Attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => ':Attribute harus sesuai dengan format :format.',
    'decimal' => ':Attribute harus memiliki :decimal tempat desimal.',
    'declined' => ':Attribute harus ditolak.',
    'declined_if' => ':Attribute harus ditolak ketika :other adalah :value.',
    'different' => ':Attribute dan :other harus berbeda.',
    'digits' => ':Attribute harus berupa :digits digit.',
    'digits_between' => ':Attribute harus memiliki panjang antara :min dan :max digit.',
    'dimensions' => ':Attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ':Attribute memiliki nilai duplikat.',
    'doesnt_end_with' => ':Attribute tidak boleh diakhiri dengan salah satu dari yang berikut: :values.',
    'doesnt_start_with' => ':Attribute tidak boleh dimulai dengan salah satu dari yang berikut: :values.',
    'email' => ':Attribute harus berupa alamat email yang valid.',
    'ends_with' => ':Attribute harus diakhiri dengan salah satu dari yang berikut: :values.',
    'enum' => ':Attribute yang dipilih tidak valid.',
    'exists' => ':Attribute yang dipilih tidak valid.',
    'extensions' => ':Attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file' => ':Attribute harus berupa file.',
    'filled' => ':Attribute harus memiliki nilai.',
    'gt' => [
        'array' => ':Attribute harus memiliki lebih dari :value item.',
        'file' => ':Attribute harus lebih besar dari :value kilobyte.',
        'numeric' => ':Attribute harus lebih besar dari :value.',
        'string' => ':Attribute harus lebih panjang dari :value karakter.',
    ],
    'gte' => [
        'array' => ':Attribute harus memiliki :value item atau lebih.',
        'file' => ':Attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => ':Attribute harus lebih besar dari atau sama dengan :value.',
        'string' => ':Attribute harus lebih panjang dari atau sama dengan :value karakter.',
    ],
    'hex_color' => ':Attribute harus berupa warna heksadesimal yang valid.',
    'image' => ':Attribute harus berupa gambar.',
    'in' => ':Attribute yang dipilih tidak valid.',
    'in_array' => ':Attribute harus ada di dalam :other.',
    'integer' => ':Attribute harus berupa bilangan bulat.',
    'ip' => ':Attribute harus berupa alamat IP yang valid.',
    'ipv4' => ':Attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => ':Attribute harus berupa alamat IPv6 yang valid.',
    'json' => ':Attribute harus berupa string JSON yang valid.',
    'list' => ':Attribute harus berupa daftar.',
    'lowercase' => ':Attribute harus berupa huruf kecil.',
    'lt' => [
        'array' => ':Attribute harus memiliki kurang dari :value item.',
        'file' => ':Attribute harus kurang dari :value kilobyte.',
        'numeric' => ':Attribute harus kurang dari :value.',
        'string' => ':Attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':Attribute tidak boleh memiliki lebih dari :value item.',
        'file' => ':Attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => ':Attribute harus kurang dari atau sama dengan :value.',
        'string' => ':Attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ':Attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => ':Attribute tidak boleh memiliki lebih dari :max item.',
        'file' => ':Attribute tidak boleh lebih besar dari :max kilobyte.',
        'numeric' => ':Attribute tidak boleh lebih besar dari :max.',
        'string' => ':Attribute tidak boleh lebih panjang dari :max karakter.',
    ],
    'max_digits' => ':Attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes' => ':Attribute harus berupa file bertipe: :values.',
    'mimetypes' => ':Attribute harus berupa file bertipe: :values.',
    'min' => [
        'array' => ':Attribute harus memiliki minimal :min item.',
        'file' => ':Attribute harus memiliki ukuran minimal :min kilobyte.',
        'numeric' => ':Attribute harus memiliki nilai minimal :min.',
        'string' => ':Attribute harus memiliki panjang minimal :min karakter.',
    ],
    'min_digits' => ':Attribute harus memiliki minimal :min digit.',
    'missing' => ':Attribute harus hilang.',
    'missing_if' => ':Attribute harus hilang ketika :other adalah :value.',
    'missing_unless' => ':Attribute harus hilang kecuali :other adalah :value.',
    'missing_with' => ':Attribute harus hilang ketika :values ada.',
    'missing_with_all' => ':Attribute harus hilang ketika :values ada.',
    'multiple_of' => ':Attribute harus merupakan kelipatan dari :value.',
    'not_in' => ':Attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':Attribute harus berupa angka.',
    'password' => [
        'letters' => ':Attribute harus mengandung minimal satu huruf.',
        'mixed' => ':Attribute harus mengandung minimal satu huruf besar dan satu huruf kecil.',
        'numbers' => ':Attribute harus mengandung minimal satu angka.',
        'symbols' => ':Attribute harus mengandung minimal satu simbol.',
        'uncompromised' => ':Attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => ':Attribute harus ada.',
    'present_if' => ':Attribute harus ada ketika :other adalah :value.',
    'present_unless' => ':Attribute harus ada kecuali :other adalah :value.',
    'present_with' => ':Attribute harus ada ketika :values ada.',
    'present_with_all' => ':Attribute harus ada ketika :values ada.',
    'prohibited' => ':Attribute dilarang.',
    'prohibited_if' => ':Attribute dilarang ketika :other adalah :value.',
    'prohibited_unless' => ':Attribute dilarang kecuali :other ada dalam :values.',
    'prohibits' => ':Attribute melarang :other untuk hadir.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':Attribute wajib diisi.',
    'required_array_keys' => ':Attribute harus berisi entri untuk: :values.',
    'required_if' => ':Attribute wajib diisi ketika :other adalah :value.',
    'required_if_accepted' => ':Attribute wajib diisi ketika :other diterima.',
    'required_if_declined' => ':Attribute wajib diisi ketika :other ditolak.',
    'required_unless' => ':Attribute wajib diisi kecuali :other ada dalam :values.',
    'required_with' => ':Attribute wajib diisi ketika :values ada.',
    'required_with_all' => ':Attribute wajib diisi ketika :values ada.',
    'required_without' => ':Attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':Attribute wajib diisi ketika tidak ada satupun dari :values yang ada.',
    'same' => ':Attribute dan :other harus cocok.',
    'size' => [
        'array' => ':Attribute harus mengandung :size item.',
        'file' => ':Attribute harus berukuran :size kilobyte.',
        'numeric' => ':Attribute harus berukuran :size.',
        'string' => ':Attribute harus berukuran :size karakter.',
    ],
    'starts_with' => ':Attribute harus dimulai dengan salah satu dari yang berikut: :values.',
    'string' => ':Attribute harus berupa string.',
    'timezone' => ':Attribute harus berupa zona waktu yang valid.',
    'unique' => ':Attribute sudah digunakan.',
    'uploaded' => ':Attribute gagal diunggah.',
    'uppercase' => ':Attribute harus berupa huruf besar.',
    'url' => ':Attribute harus berupa URL yang valid.',
    'ulid' => ':Attribute harus berupa ULID yang valid.',
    'uuid' => ':Attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom untuk atribut menggunakan
    | konvensi "attribute.rule" untuk menamai baris. Ini membuatnya cepat
    | untuk menentukan baris bahasa kustom tertentu untuk aturan atribut tertentu.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar placeholder atribut kami
    | dengan sesuatu yang lebih ramah pembaca seperti "Alamat Email" sebagai
    | pengganti "email". Ini hanya membantu kami membuat pesan kami lebih ekspresif.
    |
    */

    'attributes' => [
        'user_id' => 'ID pengguna',
        'profile' => 'profil',
        'user' => 'pengguna',
        'name' => 'nama',
        'email' => 'alamat email',
        'password' => 'kata sandi',
        'current_password' => 'kata sandi saat ini',
        'new_password' => 'kata sandi baru',
    ],

];