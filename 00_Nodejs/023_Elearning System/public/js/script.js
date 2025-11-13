class ELearningApp {
    constructor() {
        this.courses = [];
        this.init();
    }

    async init() {
        await this.loadCourses();
        this.setupEventListeners();
    }

    async loadCourses() {
        try {
            const response = await fetch('/api/courses');
            this.courses = await response.json();
            this.renderCourses();
        } catch (error) {
            console.error('Error loading courses:', error);
        }
    }

    renderCourses() {
        const coursesList = document.getElementById('courses-list');
        coursesList.innerHTML = '';

        this.courses.forEach(course => {
            const courseCard = this.createCourseCard(course);
            coursesList.appendChild(courseCard);
        });
    }

    createCourseCard(course) {
        const card = document.createElement('div');
        card.className = 'course-card';
        card.innerHTML = `
            <h3>${course.title}</h3>
            <p>${course.description}</p>
            <div class="course-meta">
                <span><strong>Instructor:</strong> ${course.instructor}</span>
                <span><strong>Duration:</strong> ${course.duration}</span>
            </div>
            <div class="course-stats">
                <span class="course-price">${course.price}</span>
                <span>👥 ${course.enrolledStudents} enrolled</span>
                <button class="btn-enroll" onclick="app.enrollInCourse(${course.id})">
                    Enroll Now
                </button>
            </div>
        `;
        return card;
    }

    async enrollInCourse(courseId) {
        try {
            const response = await fetch(`/api/courses/${courseId}/enroll`, {
                method: 'POST'
            });
            
            if (response.ok) {
                const result = await response.json();
                alert('Successfully enrolled in the course!');
                await this.loadCourses(); // Refresh the courses list
            } else {
                alert('Error enrolling in course');
            }
        } catch (error) {
            console.error('Error enrolling:', error);
            alert('Error enrolling in course');
        }
    }

    async addCourse(courseData) {
        try {
            const response = await fetch('/api/courses', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(courseData)
            });

            if (response.ok) {
                const newCourse = await response.json();
                this.courses.push(newCourse);
                this.renderCourses();
                this.resetForm();
                alert('Course added successfully!');
            } else {
                const error = await response.json();
                alert(`Error: ${error.error}`);
            }
        } catch (error) {
            console.error('Error adding course:', error);
            alert('Error adding course');
        }
    }

    resetForm() {
        document.getElementById('course-form').reset();
    }

    setupEventListeners() {
        const form = document.getElementById('course-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleFormSubmit();
        });
    }

    handleFormSubmit() {
        const courseData = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            instructor: document.getElementById('instructor').value,
            duration: document.getElementById('duration').value,
            price: document.getElementById('price').value
        };

        this.addCourse(courseData);
    }
}

// Initialize the app
const app = new ELearningApp();