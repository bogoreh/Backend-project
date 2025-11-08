<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - <?php echo $data['personal']['full_name']; ?></title>
    <style>
        .resume-template2 {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border: 2px solid #333;
            font-family: 'Georgia', serif;
        }
        .header {
            background: #333;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.2em;
            letter-spacing: 2px;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 25px;
            border-left: 3px solid #333;
            padding-left: 15px;
        }
        .section h2 {
            color: #333;
            margin-top: 0;
        }
        .contact-info {
            text-align: center;
            margin: 10px 0;
            font-style: italic;
        }
        .photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="resume-template2">
        <div class="header">
            <?php if (!empty($data['personal']['photo'])): ?>
                <img src="<?php echo $data['personal']['photo']; ?>" alt="Profile Photo" class="photo">
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($data['personal']['full_name']); ?></h1>
        </div>
        
        <div class="content">
            <div class="contact-info">
                <?php echo htmlspecialchars($data['personal']['email']); ?> | 
                <?php echo htmlspecialchars($data['personal']['phone']); ?> | 
                <?php echo htmlspecialchars($data['personal']['address']); ?>
            </div>

            <?php if (!empty($data['personal']['summary'])): ?>
            <div class="section">
                <h2>Summary</h2>
                <p><?php echo nl2br(htmlspecialchars($data['personal']['summary'])); ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['education'])): ?>
            <div class="section">
                <h2>Education</h2>
                <?php foreach ($data['education'] as $edu): ?>
                    <?php if (!empty($edu['degree'])): ?>
                    <div style="margin-bottom: 15px;">
                        <h3 style="margin: 0;"><?php echo htmlspecialchars($edu['degree']); ?></h3>
                        <p style="margin: 5px 0;"><em><?php echo htmlspecialchars($edu['institution']); ?></em> - <?php echo htmlspecialchars($edu['year']); ?></p>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['experience'])): ?>
            <div class="section">
                <h2>Experience</h2>
                <?php foreach ($data['experience'] as $exp): ?>
                    <?php if (!empty($exp['job_title'])): ?>
                    <div style="margin-bottom: 15px;">
                        <h3 style="margin: 0;"><?php echo htmlspecialchars($exp['job_title']); ?></h3>
                        <p style="margin: 5px 0;"><em><?php echo htmlspecialchars($exp['company']); ?> | <?php echo htmlspecialchars($exp['duration']); ?></em></p>
                        <p style="margin: 5px 0;"><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['skills'])): ?>
            <div class="section">
                <h2>Skills</h2>
                <ul>
                    <?php foreach ($data['skills'] as $skill): ?>
                        <?php if (!empty($skill)): ?>
                        <li><?php echo htmlspecialchars($skill); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="text-align: center; margin: 20px;">
        <form method="POST">
            <button type="submit" name="download" style="padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer;">
                Download as PDF
            </button>
            <a href="index.php" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; margin-left: 10px;">
                Create New Resume
            </a>
        </form>
    </div>
</body>
</html>