<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Post Published</title>
</head>
<body>
    <h2>{{ $post->title }}</h2>
<p>{{ $post->description }}</p>

<hr>

<p>
    Website: {{ $post->website->name }} <br>
    URL: {{ $post->website->url }}
</p>
</body>
</html>
