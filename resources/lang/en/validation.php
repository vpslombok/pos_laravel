<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute harus diterima.',
    'active_url' => 'The :attribute bukan URL yang valid.',
    'after' => 'The :attribute harus tanggal setelah :date.',
    'after_or_equal' => 'The :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => 'The :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'The :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'The :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'The :attribute harus berupa array.',
    'before' => 'The :attribute harus tanggal sebelum :date.',
    'before_or_equal' => 'The :attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => 'The :attribute harus antara :min dan :max.',
        'file' => 'The :attribute harus antara :min dan :max kilobyte.',
        'string' => 'The :attribute harus antara :min dan :max karakter.',
        'array' => 'The :attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean' => 'The :attribute harus benar atau salah.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => 'The :attribute bukan tanggal yang valid.',
    'date_equals' => 'The :attribute harus tanggal sama dengan :date.',
    'date_format' => 'The :attribute tidak cocok dengan format :format.',
    'different' => 'The :attribute dan :other harus berbeda.',
    'digits' => 'The :attribute harus :digits digit.',
    'digits_between' => 'The :attribute harus antara :min dan :max digit.',
    'dimensions' => 'The :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'The :attribute memiliki nilai duplikat.',
    'email' => 'The :attribute harus alamat email yang valid.',
    'ends_with' => 'The :attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'exists' => 'The selected :attribute tidak valid.',
    'file' => 'The :attribute harus berupa file.',
    'filled' => 'The :attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => 'The :attribute harus lebih besar dari :value.',
        'file' => 'The :attribute harus lebih besar dari :value kilobyte.',
        'string' => 'The :attribute harus lebih besar dari :value karakter.',
        'array' => 'The :attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => 'The :attribute harus lebih besar atau sama dengan :value.',
        'file' => 'The :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'string' => 'The :attribute harus lebih besar atau sama dengan :value karakter.',
        'array' => 'The :attribute harus memiliki :value item atau lebih.',
    ],
    'image' => 'The :attribute harus berupa gambar.',
    'in' => 'The selected :attribute tidak valid.',
    'in_array' => 'The :attribute tidak ada dalam :other.',
    'integer' => 'The :attribute harus berupa integer.',
    'ip' => 'The :attribute harus alamat IP yang valid.',
    'ipv4' => 'The :attribute harus alamat IPv4 yang valid.',
    'ipv6' => 'The :attribute harus alamat IPv6 yang valid.',
    'json' => 'The :attribute harus berupa string JSON yang valid.',
    'lt' => [
        'numeric' => 'The :attribute harus kurang dari :value.',
        'file' => 'The :attribute harus kurang dari :value kilobyte.',
        'string' => 'The :attribute harus kurang dari :value karakter.',
        'array' => 'The :attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'The :attribute harus kurang dari atau sama dengan :value.',
        'file' => 'The :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string' => 'The :attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => 'The :attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'max' => [
        'numeric' => 'The :attribute tidak boleh lebih besar dari :max.',
        'file' => 'The :attribute tidak boleh lebih besar dari :max kilobyte.',
        'string' => 'The :attribute tidak boleh lebih besar dari :max karakter.',
        'array' => 'The :attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes' => 'The :attribute harus berupa file tipe: :values.',
    'mimetypes' => 'The :attribute harus berupa file tipe: :values.',
    'min' => [
        'numeric' => 'The :attribute harus minimal :min.',
        'file' => 'The :attribute harus minimal :min kilobyte.',
        'string' => 'The :attribute harus minimal :min karakter.',
        'array' => 'The :attribute harus minimal :min item.',
    ],
    'multiple_of' => 'The :attribute harus kelipatan dari :value.',
    'not_in' => 'The selected :attribute tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => 'The :attribute harus berupa angka.',
    'password' => 'Password salah.',
    'present' => 'The :attribute harus hadir.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => 'The :attribute wajib diisi.',
    'required_if' => 'The :attribute wajib diisi ketika :other adalah :value.',
    'required_unless' => 'The :attribute wajib diisi kecuali :other adalah dalam :values.',
    'required_with' => 'The :attribute wajib diisi ketika :values hadir.',
    'required_with_all' => 'The :attribute wajib diisi ketika :values hadir.',
    'required_without' => 'The :attribute wajib diisi ketika :values tidak hadir.',
    'required_without_all' => 'The :attribute wajib diisi ketika tidak ada :values.',
    'same' => 'The :attribute dan :other harus cocok.',
    'size' => [
        'numeric' => 'The :attribute harus :size.',
        'file' => 'The :attribute harus :size kilobyte.',
        'string' => 'The :attribute harus :size karakter.',
        'array' => 'The :attribute harus memiliki :size item.',
    ],
    'starts_with' => 'The :attribute harus diawali dengan salah satu dari berikut: :values.',
    'string' => 'The :attribute harus berupa string.',
    'timezone' => 'The :attribute harus zona waktu yang valid.',
    'unique' => 'The :attribute telah diambil.',
    'uploaded' => 'The :attribute gagal diunggah.',
    'url' => 'Format :attribute tidak valid.',
    'uuid' => 'The :attribute harus UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
