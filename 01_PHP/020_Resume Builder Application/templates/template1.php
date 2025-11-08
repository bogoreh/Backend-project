<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - <?php echo $data['personal']['full_name']; ?></title>
    <style>
        .resume-template1 {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            font-family: 'Arial', sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .header .contact-info {
            margin-top: 10px;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .education-item, .experience-item {
            margin-bottom: 20px;
        }
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .skill-tag {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        .photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border: 5px solid white;
        }
    </style>
</head>
<body>
    <div class="resume-template1">
        <div class="header">
            <?php if (!empty($data['personal']['photo'])): ?>
                <img src="<?php echo $data['personal']['photo']; ?>" alt="Profile Photo" class="photo">
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($data['personal']['full_name']); ?></h1>
            <div class="contact-info">
                <?php echo htmlspecialchars($data['personal']['email']); ?> | 
                <?php echo htmlspecialchars($data['personal']['phone']); ?> | 
                <?php echo htmlspecialchars($data['personal']['address']); ?>
            </div>
        </div>
        
        <div class="content">
            <?php if (!empty($data['personal']['summary'])): ?>
            <div class="section">
                <h2>Professional Summary</h2>
                <p><?php echo nl2br(htmlspecialchars($data['personal']['summary'])); ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['education'])): ?>
            <div class="section">
                <h2>Education</h2>
                <?php foreach ($data['education'] as $edu): ?>
                    <?php if (!empty($edu['degree'])): ?>
                    <div class="education-item">
                        <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                        <p><strong><?php echo htmlspecialchars($edu['institution']); ?></strong> | <?php echo htmlspecialchars($edu['year']); ?></p>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['experience'])): ?>
            <div class="section">
                <h2>Work Experience</h2>
                <?php foreach ($data['experience'] as $exp): ?>
                    <?php if (!empty($exp['job_title'])): ?>
                    <div class="experience-item">
                        <h3><?php echo htmlspecialchars($exp['job_title']); ?></h3>
                        <p><strong><?php echo htmlspecialchars($exp['company']); ?></strong> | <?php echo htmlspecialchars($exp['duration']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['skills'])): ?>
            <div class="section">
                <h2>Skills</h2>
                <div class="skills">
                    <?php foreach ($data['skills'] as $skill): ?>
                        <?php if (!empty($skill)): ?>
                        <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="text-align: center; margin: 20px;">
        <form method="POST">
            <button type="submit" name="download" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Download as PDF
            </button>
            <a href="index.php" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
                Create New Resume
            </a>
        </form>
    </div>
</body>
</html>