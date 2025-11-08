<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Builder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Professional Resume Builder</h1>
        <form action="process.php" method="POST" enctype="multipart/form-data" class="resume-form">
            
            <!-- Personal Information -->
            <div class="form-section">
                <h2>Personal Information</h2>
                <div class="form-group">
                    <input type="text" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <input type="text" name="address" placeholder="Address">
                </div>
                <div class="form-group">
                    <textarea name="summary" placeholder="Professional Summary" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Profile Photo:</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
            </div>

            <!-- Education -->
            <div class="form-section">
                <h2>Education</h2>
                <div id="education-section">
                    <div class="education-item">
                        <input type="text" name="education[0][degree]" placeholder="Degree">
                        <input type="text" name="education[0][institution]" placeholder="Institution">
                        <input type="text" name="education[0][year]" placeholder="Year">
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addEducation()">Add Education</button>
            </div>

            <!-- Experience -->
            <div class="form-section">
                <h2>Work Experience</h2>
                <div id="experience-section">
                    <div class="experience-item">
                        <input type="text" name="experience[0][job_title]" placeholder="Job Title">
                        <input type="text" name="experience[0][company]" placeholder="Company">
                        <input type="text" name="experience[0][duration]" placeholder="Duration">
                        <textarea name="experience[0][description]" placeholder="Job Description" rows="3"></textarea>
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addExperience()">Add Experience</button>
            </div>

            <!-- Skills -->
            <div class="form-section">
                <h2>Skills</h2>
                <div id="skills-section">
                    <div class="skill-item">
                        <input type="text" name="skills[]" placeholder="Skill">
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addSkill()">Add Skill</button>
            </div>

            <!-- Template Selection -->
            <div class="form-section">
                <h2>Choose Template</h2>
                <div class="template-selection">
                    <label class="template-option">
                        <input type="radio" name="template" value="template1" checked>
                        <div class="template-preview">
                            <h4>Modern</h4>
                            <div class="preview-box modern-preview"></div>
                        </div>
                    </label>
                    <label class="template-option">
                        <input type="radio" name="template" value="template2">
                        <div class="template-preview">
                            <h4>Classic</h4>
                            <div class="preview-box classic-preview"></div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="submit-btn">Generate Resume</button>
        </form>
    </div>

    <script>
        let educationCount = 1;
        let experienceCount = 1;
        let skillCount = 1;

        function addEducation() {
            const section = document.getElementById('education-section');
            const newItem = document.createElement('div');
            newItem.className = 'education-item';
            newItem.innerHTML = `
                <input type="text" name="education[${educationCount}][degree]" placeholder="Degree">
                <input type="text" name="education[${educationCount}][institution]" placeholder="Institution">
                <input type="text" name="education[${educationCount}][year]" placeholder="Year">
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">Remove</button>
            `;
            section.appendChild(newItem);
            educationCount++;
        }

        function addExperience() {
            const section = document.getElementById('experience-section');
            const newItem = document.createElement('div');
            newItem.className = 'experience-item';
            newItem.innerHTML = `
                <input type="text" name="experience[${experienceCount}][job_title]" placeholder="Job Title">
                <input type="text" name="experience[${experienceCount}][company]" placeholder="Company">
                <input type="text" name="experience[${experienceCount}][duration]" placeholder="Duration">
                <textarea name="experience[${experienceCount}][description]" placeholder="Job Description" rows="3"></textarea>
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">Remove</button>
            `;
            section.appendChild(newItem);
            experienceCount++;
        }

        function addSkill() {
            const section = document.getElementById('skills-section');
            const newItem = document.createElement('div');
            newItem.className = 'skill-item';
            newItem.innerHTML = `
                <input type="text" name="skills[]" placeholder="Skill">
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">Remove</button>
            `;
            section.appendChild(newItem);
            skillCount++;
        }
    </script>
</body>
</html>