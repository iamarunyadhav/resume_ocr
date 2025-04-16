<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist Resume</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.5;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .name {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .title {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            color: #444;
        }
        .experience-item, .education-item {
            margin-bottom: 20px;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .item-title {
            font-weight: 600;
        }
        .item-date {
            color: #666;
        }
        .item-subtitle {
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .item-description {
            font-size: 14px;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .skill {
            padding: 3px 8px;
            font-size: 13px;
        }
        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">{{ $data['name'] ?? 'Your Name' }}</div>
        <div class="title">{{ $data['title'] ?? 'Professional Title' }}</div>
        <div class="contact-info">
            @if(isset($data['email']))
                <span>{{ $data['email'] }}</span>
            @endif
            @if(isset($data['phone']))
                <span>|</span>
                <span>{{ $data['phone'] }}</span>
            @endif
            @if(isset($data['location']))
                <span>|</span>
                <span>{{ $data['location'] }}</span>
            @endif
        </div>
    </div>

    <hr>

    @if(isset($data['summary']))
        <div class="section">
            <p>{{ $data['summary'] }}</p>
        </div>
        <hr>
    @endif

    @if(isset($data['experience']))
        <div class="section">
            <div class="section-title">Experience</div>
            @foreach($data['experience'] as $exp)
                <div class="experience-item">
                    <div class="item-header">
                        <div class="item-title">{{ $exp['job_title'] ?? 'Position' }}</div>
                        <div class="item-date">{{ $exp['dates'] ?? 'Dates' }}</div>
                    </div>
                    <div class="item-subtitle">{{ $exp['company'] ?? 'Company' }}</div>
                    @if(isset($exp['description']))
                        <div class="item-description">
                            @if(is_array($exp['description']))
                                <ul style="margin-left: 20px; padding-left: 0; list-style-type: none;">
                                    @foreach($exp['description'] as $point)
                                        <li style="margin-bottom: 5px;">• {{ $point }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $exp['description'] }}
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <hr>
    @endif

    @if(isset($data['education']))
        <div class="section">
            <div class="section-title">Education</div>
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
        <hr>
    @endif

    @if(isset($data['skills']))
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-list">
                @foreach($data['skills'] as $skill)
                    <div class="skill">{{ $skill }}</div>
                @endforeach
            </div>
        </div>
    @endif
</body>
</html>
