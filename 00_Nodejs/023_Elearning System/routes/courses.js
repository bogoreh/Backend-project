const express = require('express');
const fs = require('fs');
const path = require('path');
const router = express.Router();

const coursesFile = path.join(__dirname, '../data/courses.json');

// Helper function to read courses
const readCourses = () => {
    try {
        const data = fs.readFileSync(coursesFile, 'utf8');
        return JSON.parse(data);
    } catch (error) {
        return [];
    }
};

// Helper function to write courses
const writeCourses = (courses) => {
    fs.writeFileSync(coursesFile, JSON.stringify(courses, null, 2));
};

// Get all courses
router.get('/', (req, res) => {
    const courses = readCourses();
    res.json(courses);
});

// Get course by ID
router.get('/:id', (req, res) => {
    const courses = readCourses();
    const course = courses.find(c => c.id === parseInt(req.params.id));
    if (!course) {
        return res.status(404).json({ error: 'Course not found' });
    }
    res.json(course);
});

// Add new course
router.post('/', (req, res) => {
    const { title, description, instructor, duration, price } = req.body;
    
    if (!title || !description || !instructor) {
        return res.status(400).json({ error: 'Title, description, and instructor are required' });
    }

    const courses = readCourses();
    const newCourse = {
        id: courses.length > 0 ? Math.max(...courses.map(c => c.id)) + 1 : 1,
        title,
        description,
        instructor,
        duration: duration || 'Not specified',
        price: price || 'Free',
        enrolledStudents: 0,
        createdAt: new Date().toISOString()
    };

    courses.push(newCourse);
    writeCourses(courses);
    res.status(201).json(newCourse);
});

// Enroll in course
router.post('/:id/enroll', (req, res) => {
    const courses = readCourses();
    const courseIndex = courses.findIndex(c => c.id === parseInt(req.params.id));
    
    if (courseIndex === -1) {
        return res.status(404).json({ error: 'Course not found' });
    }

    courses[courseIndex].enrolledStudents += 1;
    writeCourses(courses);
    res.json({ message: 'Successfully enrolled in course', course: courses[courseIndex] });
});

module.exports = router;