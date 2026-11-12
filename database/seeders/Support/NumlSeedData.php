<?php

namespace Database\Seeders\Support;

use App\Models\MyClass;
use App\User;
use Illuminate\Support\Str;

/** Editable FYP data, not an official NUML curriculum or staff list. */
class NumlSeedData
{
    const SESSION = '2023-2027';
    const NAMED_STUDENTS_SEMESTER = 6;
    const STUDENTS_PER_SECTION = 10;
    const SECTIONS = ['A', 'B'];

    public static function departments()
    {
        return [
            'CS' => 'Computer Science', 'SE' => 'Software Engineering',
            'IT' => 'Information Technology', 'AI' => 'Artificial Intelligence',
            'ENG' => 'English', 'PSY' => 'Psychology', 'MS' => 'Management Sciences',
        ];
    }

    public static function faculty()
    {
        // First two teachers are the sample HOD and coordinator, respectively.
        // Appointments and departments are illustrative, including for supplied names.
        return [
            'CS' => ['Mr Faisal Hussain', 'Dr Rao Kashif', "Ma'am Neelofer", 'Mr Kashif', 'M Zainulabideen', 'Mustajeeb ur Rehman'],
            'SE' => ['Dr Ayesha Ahmed', 'Mr Salman Akhtar', 'Ms Hira Mahmood', 'Mr Farhan Ali'],
            'IT' => ['Dr Nadia Iqbal', 'Mr Bilal Shah', 'Ms Sana Khalid', 'Mr Usman Tariq'],
            'AI' => ['Dr Ahmed Raza', 'Ms Fatima Noor', 'Mr Hassan Javed', 'Ms Iqra Saleem'],
            'ENG' => ['Dr Saima Malik', 'Mr Adnan Qureshi', 'Ms Mahnoor Abbas', 'Mr Shahid Khan'],
            'PSY' => ['Dr Rabia Aslam', 'Ms Saba Yousaf', 'Mr Waqas Anwar', 'Ms Amna Rauf'],
            'MS' => ['Dr Kamran Saeed', 'Ms Nida Hassan', 'Mr Imran Siddiqui', 'Ms Bushra Akram'],
        ];
    }

    public static function staffCode($department, $index)
    {
        return sprintf('NUML-STAFF-%s-%02d', $department, $index + 1);
    }

    public static function teachers($department)
    {
        $teachers = [];
        foreach (self::faculty()[$department] as $index => $name) {
            $teachers[] = User::where('code', self::staffCode($department, $index))
                ->where('user_type', 'teacher')->firstOrFail();
        }
        return $teachers;
    }

    public static function className($department, $semester)
    {
        return $department.' - Semester '.$semester;
    }

    public static function classDetails(MyClass $class)
    {
        $department = $class->class_type->code;
        if (!isset(self::departments()[$department]) ||
            !preg_match('/^'.preg_quote($department, '/').' - Semester ([1-8])$/', $class->name, $matches)) {
            throw new \InvalidArgumentException('This class is not a NUML demonstration semester.');
        }
        return [$department, (int) $matches[1]];
    }

    public static function classes()
    {
        $names = [];
        foreach (self::departments() as $code => $name) {
            for ($semester = 1; $semester <= 8; $semester++) {
                $names[] = self::className($code, $semester);
            }
        }
        return MyClass::with('class_type')->whereHas('class_type', function ($query) {
            $query->whereIn('code', array_keys(self::departments()));
        })->whereIn('name', $names)->orderBy('id')->get();
    }

    public static function seedUser($code, $name, $type, $password, array $attributes = [])
    {
        // Strip titles and the abbreviated M initial for an easy first-name login.
        $plainName = preg_replace('/^(?:(?:mr|mrs|ms|dr|prof|ma\x27am|m)\.?\s+)+/i', '', $name);
        $firstName = Str::slug(explode(' ', trim($plainName))[0], '');
        $username = $firstName;
        $suffix = 2;
        while (User::where('username', $username)->where('code', '<>', $code)->exists()) {
            $username = $firstName.$suffix++;
        }
        return User::updateOrCreate(['code' => $code], array_merge($attributes, [
            'name' => $name, 'username' => $username,
            'email' => strtolower($code).'@numl.example.test',
            'user_type' => $type, 'password' => $password,
        ]));
    }

    public static function namedStudents()
    {
        return ['Marjan', 'Mateen', 'Mudassir', 'Sheeza', 'Asad Ali Arif', 'Faiq Riaz'];
    }

    public static function studentName($index)
    {
        $firstNames = [
            'Ali', 'Areeba', 'Hamza', 'Ayesha', 'Hassan', 'Fatima', 'Hussain', 'Zainab',
            'Umar', 'Maryam', 'Usman', 'Hafsa', 'Bilal', 'Iqra', 'Talha', 'Hira',
            'Saad', 'Sana', 'Abdullah', 'Mahnoor', 'Ibrahim', 'Noor', 'Ahmed', 'Eman',
            'Zain', 'Laiba', 'Daniyal', 'Amna', 'Huzaifa', 'Anum', 'Rayyan', 'Nimra',
            'Shahzaib', 'Maham', 'Arslan', 'Alina', 'Fahad', 'Sadia', 'Ammar', 'Kinza',
        ];
        $lastNames = [
            'Khan', 'Ahmed', 'Malik', 'Shah', 'Abbasi', 'Qureshi', 'Iqbal', 'Raza',
            'Farooq', 'Tariq', 'Javed', 'Saeed', 'Akhtar', 'Aslam', 'Yousaf', 'Anwar',
            'Rauf', 'Hussain', 'Siddiqui', 'Chaudhry', 'Butt', 'Awan', 'Rasheed', 'Nawaz',
            'Saleem', 'Bashir', 'Khalid', 'Mehmood', 'Sohail', 'Rehman', 'Naeem', 'Arif',
        ];
        return $firstNames[$index % count($firstNames)].' '.
            $lastNames[(int) floor($index / count($firstNames)) % count($lastNames)];
    }

    public static function curriculum($department, $semester)
    {
        return self::curricula()[$department][$semester];
    }

    public static function courseDescription($department, $semester, $title)
    {
        $source = $department === 'CS' && $semester <= 6
            ? 'Subject supplied by the project owner.'
            : 'Sample curriculum for the FYP; replace with the approved department syllabus.';
        return $source.' Session '.self::SESSION.'.'.
            (strpos($title, '(Lab)') !== false ? ' Separate laboratory subject.' : '').
            (strpos($title, '(Non-Credited)') !== false ? ' Non-credited deficiency subject.' : '').
            ' Course codes are demonstration identifiers.';
    }

    private static function curricula()
    {
        return [
            'CS' => [
                1 => [
                    'Introduction to Information and Communication Technology',
                    'Introduction to Information and Communication Technology (Lab)',
                    'Pakistan Studies', 'English Comprehensive and Comprehensive',
                    'Programing Fundamental', 'Programing Fundamental (Lab)',
                    'Islamic Studies', 'Applied Physics', 'Pre Math - I (Non-Credited)',
                ],
                2 => [
                    'Calculus and Analytical Geometry', 'Object Oriented Programming',
                    'Object Oriented Programming (Lab)', 'Communication and Presentation Skills',
                    'Intro to Psychology', 'Discrete Structures', 'Social Service', 'Pre Math - II (Non-Credited)',
                ],
                3 => [
                    'Data Structures & Algorithms', 'Data Structures & Algorithms (Lab)',
                    'Multivariable Calculus', 'Professional Practices', 'Linear Algebra',
                    'Digital Logic & Design', 'Digital Logic & Design (Lab)',
                ],
                4 => [
                    'Differential Equation', 'Design & Analysis of Algorithms', 'Web Programming',
                    'Web Programming (Lab)', 'Software Engineering', 'Advanced OOP',
                    'Advanced OOP (Lab)', 'Digital Marketing',
                ],
                5 => [
                    'Operating System', 'Operating System (Lab)', 'Database System', 'Database System (Lab)',
                    'Statistics & Probability', 'Numerical Computing',
                    'Computer Organization and Assembly Language', 'Computer Organization and Assembly Language (Lab)',
                ],
                6 => [
                    'Automata Theory', 'Artificial Intelligence', 'Artificial Intelligence (Lab)',
                    'Technical & Business Writing', 'Computer Networks', 'Computer Networks (Lab)',
                    'Visual Programming', 'Visual Programming (Lab)',
                ],
                // Everything not supplied by the owner is explicitly sample data.
                7 => ['Compiler Construction', 'Information Security', 'Human Computer Interaction', 'Software Project Management', 'Final Year Project - I'],
                8 => ['Cloud Computing', 'Mobile Application Development', 'Mobile Application Development (Lab)', 'Entrepreneurship', 'Final Year Project - II'],
            ],
            'SE' => [
                1 => ['Programming Fundamentals', 'Programming Fundamentals (Lab)', 'Introduction to Software Engineering', 'Functional English', 'Calculus', 'Islamic Studies'],
                2 => ['Object Oriented Programming', 'Object Oriented Programming (Lab)', 'Discrete Structures', 'Communication Skills', 'Pakistan Studies', 'Linear Algebra'],
                3 => ['Data Structures & Algorithms', 'Data Structures & Algorithms (Lab)', 'Software Requirements Engineering', 'Digital Logic Design', 'Probability and Statistics'],
                4 => ['Database Systems', 'Database Systems (Lab)', 'Software Design and Architecture', 'Web Engineering', 'Web Engineering (Lab)', 'Technical Writing'],
                5 => ['Operating Systems', 'Operating Systems (Lab)', 'Software Construction', 'Software Quality Engineering', 'Computer Networks'],
                6 => ['Software Testing', 'Software Testing (Lab)', 'Software Project Management', 'Human Computer Interaction', 'Software Configuration Management'],
                7 => ['Software Reengineering', 'Information Security', 'DevOps', 'DevOps (Lab)', 'Final Year Project - I'],
                8 => ['Software Process Improvement', 'Cloud Application Development', 'Professional Practices', 'Entrepreneurship', 'Final Year Project - II'],
            ],
            'IT' => [
                1 => ['Introduction to Information Technology', 'Introduction to Information Technology (Lab)', 'Programming Fundamentals', 'Programming Fundamentals (Lab)', 'Functional English', 'Islamic Studies'],
                2 => ['Object Oriented Programming', 'Object Oriented Programming (Lab)', 'Calculus', 'Discrete Mathematics', 'Communication Skills', 'Pakistan Studies'],
                3 => ['Data Structures', 'Data Structures (Lab)', 'Database Systems', 'Database Systems (Lab)', 'Computer Organization'],
                4 => ['Web Technologies', 'Web Technologies (Lab)', 'Operating Systems', 'Operating Systems (Lab)', 'Probability and Statistics'],
                5 => ['Computer Networks', 'Computer Networks (Lab)', 'System and Network Administration', 'Information Systems', 'Technical Writing'],
                6 => ['IT Infrastructure', 'Information Security', 'Mobile Application Development', 'Mobile Application Development (Lab)', 'IT Project Management'],
                7 => ['Cloud Computing', 'Cloud Computing (Lab)', 'E-Commerce', 'Data Warehousing', 'Final Year Project - I'],
                8 => ['IT Governance', 'Enterprise Systems', 'Professional Practices', 'Entrepreneurship', 'Final Year Project - II'],
            ],
            'AI' => [
                1 => ['Programming Fundamentals', 'Programming Fundamentals (Lab)', 'Introduction to Artificial Intelligence', 'Calculus', 'Functional English', 'Islamic Studies'],
                2 => ['Object Oriented Programming', 'Object Oriented Programming (Lab)', 'Linear Algebra', 'Discrete Structures', 'Communication Skills', 'Pakistan Studies'],
                3 => ['Data Structures & Algorithms', 'Data Structures & Algorithms (Lab)', 'Probability and Statistics', 'Multivariable Calculus', 'Digital Logic Design'],
                4 => ['Database Systems', 'Database Systems (Lab)', 'Knowledge Representation and Reasoning', 'Numerical Computing', 'Design and Analysis of Algorithms'],
                5 => ['Machine Learning', 'Machine Learning (Lab)', 'Artificial Neural Networks', 'Operating Systems', 'Technical Writing'],
                6 => ['Deep Learning', 'Deep Learning (Lab)', 'Natural Language Processing', 'Computer Vision', 'AI Ethics'],
                7 => ['Reinforcement Learning', 'Robotics', 'Robotics (Lab)', 'Big Data Analytics', 'Final Year Project - I'],
                8 => ['Generative AI', 'AI Deployment', 'Professional Practices', 'Entrepreneurship', 'Final Year Project - II'],
            ],
            'ENG' => [
                1 => ['Introduction to Literature', 'Introduction to Linguistics', 'Functional English', 'Study Skills', 'Islamic Studies'],
                2 => ['English Grammar', 'Phonetics and Phonology', 'Introduction to Poetry', 'Communication Skills', 'Pakistan Studies'],
                3 => ['History of English Literature', 'Morphology', 'Classical Poetry', 'Academic Writing', 'Introduction to Psychology'],
                4 => ['Syntax', 'Classical Drama', 'Prose', 'Sociolinguistics', 'Literary Criticism'],
                5 => ['Semantics', 'Romantic Poetry', 'The English Novel', 'Applied Linguistics', 'Research Methods'],
                6 => ['Pragmatics', 'Modern Drama', 'American Literature', 'Discourse Analysis', 'English Language Teaching'],
                7 => ['Postcolonial Literature', 'Literary Theory', 'Pakistani Literature in English', 'Translation Studies', 'Research Project - I'],
                8 => ['World Literature', 'Stylistics', 'Contemporary Fiction', 'Professional Writing', 'Research Project - II'],
            ],
            'PSY' => [
                1 => ['Introduction to Psychology', 'Biological Basis of Behavior', 'Functional English', 'Basic Mathematics', 'Islamic Studies'],
                2 => ['Developmental Psychology', 'Social Psychology', 'Statistics in Psychology', 'Communication Skills', 'Pakistan Studies'],
                3 => ['Cognitive Psychology', 'Personality Psychology', 'Experimental Psychology', 'Experimental Psychology (Lab)', 'Academic Writing'],
                4 => ['Abnormal Psychology', 'Psychological Assessment', 'Psychological Assessment (Lab)', 'Research Methods', 'Health Psychology'],
                5 => ['Clinical Psychology', 'Counseling Psychology', 'Psychometrics', 'Educational Psychology', 'Ethics in Psychology'],
                6 => ['Organizational Psychology', 'Neuropsychology', 'Qualitative Research', 'Community Psychology', 'Behavior Modification'],
                7 => ['Forensic Psychology', 'Positive Psychology', 'Advanced Research Methods', 'Supervised Practicum', 'Research Project - I'],
                8 => ['Psychotherapy', 'Cross Cultural Psychology', 'Rehabilitation Psychology', 'Internship', 'Research Project - II'],
            ],
            'MS' => [
                1 => ['Principles of Management', 'Introduction to Business', 'Financial Accounting - I', 'Functional English', 'Islamic Studies'],
                2 => ['Principles of Marketing', 'Financial Accounting - II', 'Business Mathematics', 'Communication Skills', 'Pakistan Studies'],
                3 => ['Microeconomics', 'Business Statistics', 'Organizational Behavior', 'Cost Accounting', 'Business Communication'],
                4 => ['Macroeconomics', 'Human Resource Management', 'Business Finance', 'Business Law', 'Management Information Systems'],
                5 => ['Financial Management', 'Marketing Management', 'Operations Management', 'Business Research Methods', 'Entrepreneurship'],
                6 => ['Strategic Management', 'Consumer Behavior', 'Supply Chain Management', 'Business Ethics', 'Project Management'],
                7 => ['International Business', 'Investment Analysis', 'Leadership', 'Digital Marketing', 'Business Project - I'],
                8 => ['Corporate Governance', 'Change Management', 'Business Analytics', 'Internship', 'Business Project - II'],
            ],
        ];
    }
}
