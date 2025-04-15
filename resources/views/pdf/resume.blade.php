<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resume PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { color: #1E3A8A; }
        .section { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Resume</h1>

    @foreach($data as $key => $value)
        <div class="section">
            <h2>{{ ucfirst(str_replace('_', ' ', $key)) }}</h2>
            @if(is_array($value))
                @if(isset($value[0]) && is_array($value[0]))
                    @foreach($value as $item)
                        <ul>
                            @foreach($item as $subKey => $subValue)
                                <li><strong>{{ ucfirst($subKey) }}:</strong> {{ is_array($subValue) ? implode(', ', $subValue) : $subValue }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                @else
                    {{ implode(', ', $value) }}
                @endif
            @else
                <p>{{ $value }}</p>
            @endif
        </div>
    @endforeach
</body>
</html>
