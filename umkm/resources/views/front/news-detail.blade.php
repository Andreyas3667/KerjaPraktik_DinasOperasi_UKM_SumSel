{{-- filepath: c:\Users\User\Documents\Andreyas\Kuliah\Semester 6\KerjaPraktik_DinasOperasi_UKM_SumSel\umkm\resources\views\front\news-detail.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }}</title>
</head>
<body>
    <h1>{{ $news->title }}</h1>
    <p>{{ $news->content }}</p>
    <small>Published at: {{ $news->published_at }}</small>
    @if ($news->image)
        <img src="{{ $news->image }}" alt="{{ $news->title }}" style="max-width: 100%;">
    @endif
</body>
</html>
