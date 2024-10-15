<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload XML files</title>
</head>
<body>
    <div>
        <a href="{{ route('index') }}">Home</a>
        <form action="{{ route('store_files') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="files">Escolha os arquivos. Apenas formato <strong>.xml</strong>:</label><br>
            <input type="file" name="files[]" id="files" multiple><br>
            @error('files')
                <p>{{ $message }}</p>
            @enderror
            <button type="submit">Enviar</button>
        </form>
    </div>  
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

</body> 
</html>