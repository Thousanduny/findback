@php
    $score = $rating ?? 0;
    if ($score >= 100) {
        $rank = 'Легенда';
        $color = 'text-danger'; // Красный
        $icon = '👑';
    } elseif ($score >= 50) {
        $rank = 'Эксперт';
        $color = 'text-warning'; // Желтый
        $icon = '🌟';
    } elseif ($score >= 30) {
        $rank = 'Опытный';
        $color = 'text-info'; // Голубой
        $icon = '⭐';
    } elseif ($score >= 10) {
        $rank = 'Надежный';
        $color = 'text-success'; // Зеленый
        $icon = '✅';
    } else {
        $rank = 'Новичок';
        $color = 'text-secondary'; // Серый
        $icon = '🌱';
    }
@endphp

<span class="{{ $color }} fw-bold" style="font-size: 0.85rem;">
    {{ $icon }} {{ $rank }} ({{ $score }})
</span>
