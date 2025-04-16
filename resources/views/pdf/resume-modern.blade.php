<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .name {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .title {
            font-size: 18px;
            color: #64748b;
        }
        .contact-info {
            text-align: right;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e3a8a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .experience-item, .education-item {
            margin-bottom: 20px;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .item-title {
            font-weight: 600;
            font-size: 16px;
        }
        .item-date {
            color: #64748b;
        }
        .item-subtitle {
            font-style: italic;
            margin-bottom: 10px;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .skill {
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="name">{{ $data['name'] ?? 'Your Name' }}</div>
            <div class="title">{{ $data['title'] ?? 'Professional Title' }}</div>
        </div>
        <div class="contact-info">
            @if(isset($data['email']))
                <div>{{ $data['email'] }}</div>
            @endif
            @if(isset($data['phone']))
                <div>{{ $data['phone'] }}</div>
            @endif
            @if(isset($data['location']))
                <div>{{ $data['location'] }}</div>
            @endif
        </div>
    </div>

    @if(isset($data['summary']))
        <div class="section">
            <div class="section-title">PROFESSIONAL SUMMARY</div>
            <p>{{ $data['summary'] }}</p>
        </div>
    @endif

    @if(isset($data['experience']))
        <div class="section">
            <div class="section-title">WORK EXPERIENCE</div>
            @foreach($data['experience'] as $exp)
                <div class="experience-item">
                    <div class="item-header">
                        <div class="item-title">{{ $exp['job_title'] ?? 'Position' }}</div>
                        <div class="item-date">{{ $exp['dates'] ?? 'Dates' }}</div>
                    </div>
                    <div class="item-subtitle">{{ $exp['company'] ?? 'Company' }}</div>
                    @if(isset($exp['description']))
                        <ul>
                            @if(is_array($exp['description']))
                                @foreach($exp['description'] as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            @else
                                <li>{{ $exp['description'] }}</li>
                            @endif
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(isset($data['education']))
        <div class="section">
            <div class="section-title">EDUCATION</div>
            @foreach($data['education'] as $edu)
                <div class="education-item">
                    <div class="item-header">
                        <div class="item-title">{{ $edu['degree'] ?? 'Degree' }}</div>
                        <div class="item-date">{{ $edu['dates'] ?? 'Dates' }}</div>
                    </div>
                    <div class="item-subtitle">{{ $edu['institution'] ?? 'Institution' }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(isset($data['skills']))
        <div class="section">
            <div class="section-title">SKILLS</div>
            <div class="skills-list">
                @foreach($data['skills'] as $skill)
                    <div class="skill">{{ $skill }}</div>
                @endforeach
            </div>
        </div>
    @endif
</body>
</html>
