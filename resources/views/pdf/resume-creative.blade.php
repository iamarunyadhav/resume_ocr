<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Resume</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .name {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .title {
            font-size: 20px;
            opacity: 0.9;
        }
        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .section {
            margin-bottom: 25px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #10b981;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #d1fae5;
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
            color: #047857;
        }
        .item-date {
            color: #64748b;
        }
        .item-subtitle {
            font-style: italic;
            margin-bottom: 10px;
            color: #475569;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .skill {
            background-color: #d1fae5;
            color: #047857;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">{{ $data['name'] ?? 'Your Name' }}</div>
        <div class="title">{{ $data['title'] ?? 'Professional Title' }}</div>
        <div class="contact-info">
            @if(isset($data['email']))
                <div class="contact-item">
                    <span>{{ $data['email'] }}</span>
                </div>
            @endif
            @if(isset($data['phone']))
                <div class="contact-item">
                    <span>{{ $data['phone'] }}</span>
                </div>
            @endif
            @if(isset($data['location']))
                <div class="contact-item">
                    <span>{{ $data['location'] }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="two-column">
        <div>
            @if(isset($data['summary']))
                <div class="section">
                    <div class="section-title">ABOUT ME</div>
                    <p>{{ $data['summary'] }}</p>
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
        </div>

        <div>
            @if(isset($data['experience']))
                <div class="section">
                    <div class="section-title">EXPERIENCE</div>
                    @foreach($data['experience'] as $exp)
                        <div class="experience-item">
                            <div class="item-header">
                                <div class="item-title">{{ $exp['job_title'] ?? 'Position' }}</div>
                                <div class="item-date">{{ $exp['dates'] ?? 'Dates' }}</div>
                            </div>
                            <div class="item-subtitle">{{ $exp['company'] ?? 'Company' }}</div>
                            @if(isset($exp['description']))
                                <ul style="margin-left: 20px; padding-left: 0;">
                                    @if(is_array($exp['description']))
                                        @foreach($exp['description'] as $point)
                                            <li style="margin-bottom: 5px;">{{ $point }}</li>
                                        @endforeach
                                    @else
                                        <li style="margin-bottom: 5px;">{{ $exp['description'] }}</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

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
</body>
</html>
