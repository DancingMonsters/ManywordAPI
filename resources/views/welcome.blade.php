<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{route('wordAdd')}}" method="PUT">
        <input type="text" name="word">
        <input type="text" name="particle">
        <select name="language">
            <option value="RU">Русский</option>
            <option value="EN">Английский</option>
        </select>
        <button type="submit">Добавить</button>
    </form>
</body>
</html>