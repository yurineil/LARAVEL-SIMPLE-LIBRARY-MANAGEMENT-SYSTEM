<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create or reset admin (email: admin@library.com, password: password)
        User::updateOrCreate(
            ['email' => 'admin@library.com'],
            ['name' => 'Librarian', 'password' => Hash::make('password'), 'role' => 'admin', 'approved_at' => now()]
        );

        Student::updateOrCreate(['student_id' => 'STU001'], ['name' => 'John Smith', 'email' => 'john@example.com']);
        Student::updateOrCreate(['student_id' => 'STU002'], ['name' => 'Mary Johnson', 'email' => 'mary@example.com']);
        Student::updateOrCreate(['student_id' => 'STU003'], ['name' => 'Peter Brown', 'email' => null]);

        $authorNames = [
            'Harper Lee', 'F. Scott Fitzgerald', 'Jane Austen', 'George Orwell', 'Charles Dickens',
            'Charlotte Bronte', 'Emily Bronte', 'Mark Twain', 'Ernest Hemingway', 'John Steinbeck',
            'Virginia Woolf', 'Oscar Wilde', 'Lewis Carroll', 'Mary Shelley', 'Bram Stoker',
            'Robert C. Martin', 'Thomas H. Cormen', 'Martin Fowler', 'Eric Freeman', 'Andrew Hunt',
            'Kent Beck', 'Steve McConnell', 'Donald Knuth', 'Brian Kernighan', 'Bjarne Stroustrup',
            'James Gosling', 'Herbert Schildt', 'Paul Deitel', 'John Resig', 'Nicholas Zakas',
        ];
        $authors = [];
        foreach ($authorNames as $name) {
            $authors[] = Author::firstOrCreate(['name' => $name]);
        }

        $booksData = $this->getEnglishBooksData();
        foreach ($booksData as $b) {
            $authorIndexes = $b['author_index'];
            unset($b['author_index']);
            $book = Book::firstOrCreate(
                ['title' => $b['title']],
                [
                    'category' => $b['category'],
                    'description' => $b['description'],
                    'copies' => $b['copies'] ?? 3,
                ]
            );
            $book->authors()->syncWithoutDetaching(
                array_map(fn ($i) => $authors[$i]->id, $authorIndexes)
            );
        }
    }

    /** @return array<int, array{title: string, category: string, description: string, copies?: int, author_index: array<int>}> */
    private function getEnglishBooksData(): array
    {
        return [
            ['title' => 'To Kill a Mockingbird', 'category' => 'English', 'description' => 'A classic novel about racial injustice in the American South.', 'copies' => 5, 'author_index' => [0]],
            ['title' => 'The Great Gatsby', 'category' => 'English', 'description' => 'A story of the mysterious millionaire Jay Gatsby and the American dream.', 'copies' => 4, 'author_index' => [1]],
            ['title' => 'Pride and Prejudice', 'category' => 'English', 'description' => 'A romantic novel of manners by Jane Austen.', 'copies' => 4, 'author_index' => [2]],
            ['title' => '1984', 'category' => 'English', 'description' => 'A dystopian novel about totalitarianism and surveillance.', 'copies' => 6, 'author_index' => [3]],
            ['title' => 'Animal Farm', 'category' => 'English', 'description' => 'Political satire using farm animals.', 'copies' => 5, 'author_index' => [3]],
            ['title' => 'Great Expectations', 'category' => 'English', 'description' => 'A coming-of-age story set in Victorian England.', 'copies' => 4, 'author_index' => [4]],
            ['title' => 'Jane Eyre', 'category' => 'English', 'description' => 'A novel about an orphan girl and her growth to adulthood.', 'copies' => 4, 'author_index' => [5]],
            ['title' => 'Wuthering Heights', 'category' => 'English', 'description' => 'A tale of passion and revenge on the Yorkshire moors.', 'copies' => 3, 'author_index' => [6]],
            ['title' => 'The Adventures of Tom Sawyer', 'category' => 'English', 'description' => 'A boy growing up along the Mississippi River.', 'copies' => 5, 'author_index' => [7]],
            ['title' => 'The Old Man and the Sea', 'category' => 'English', 'description' => 'An aging fisherman and his battle with a giant marlin.', 'copies' => 4, 'author_index' => [8]],
            ['title' => 'Of Mice and Men', 'category' => 'English', 'description' => 'Two migrant workers during the Great Depression.', 'copies' => 5, 'author_index' => [9]],
            ['title' => 'Mrs Dalloway', 'category' => 'English', 'description' => 'A day in the life of Clarissa Dalloway in post-WWI London.', 'copies' => 3, 'author_index' => [10]],
            ['title' => 'The Picture of Dorian Gray', 'category' => 'English', 'description' => 'A man who stays young while his portrait ages.', 'copies' => 4, 'author_index' => [11]],
            ['title' => 'Alice in Wonderland', 'category' => 'English', 'description' => 'A girl falls into a fantasy world of strange creatures.', 'copies' => 6, 'author_index' => [12]],
            ['title' => 'Frankenstein', 'category' => 'English', 'description' => 'A scientist creates a living creature from dead matter.', 'copies' => 4, 'author_index' => [13]],
            ['title' => 'Dracula', 'category' => 'English', 'description' => 'The vampire Count Dracula and his move to England.', 'copies' => 4, 'author_index' => [14]],
            ['title' => 'Clean Code', 'category' => 'Computer Science', 'description' => 'A handbook of agile software craftsmanship.', 'copies' => 3, 'author_index' => [15]],
            ['title' => 'Introduction to Algorithms', 'category' => 'Computer Science', 'description' => 'Comprehensive textbook on algorithms and data structures.', 'copies' => 3, 'author_index' => [16]],
            ['title' => 'Refactoring', 'category' => 'Computer Science', 'description' => 'Improving the design of existing code.', 'copies' => 3, 'author_index' => [17]],
            ['title' => 'Head First Design Patterns', 'category' => 'Computer Science', 'description' => 'Object-oriented design patterns explained with examples.', 'copies' => 4, 'author_index' => [18]],
            ['title' => 'The Pragmatic Programmer', 'category' => 'Computer Science', 'description' => 'Tips and practices for better software development.', 'copies' => 4, 'author_index' => [19]],
            ['title' => 'Test Driven Development', 'category' => 'Computer Science', 'description' => 'By example. Writing tests before code.', 'copies' => 3, 'author_index' => [20]],
            ['title' => 'Code Complete', 'category' => 'Computer Science', 'description' => 'A practical handbook of software construction.', 'copies' => 3, 'author_index' => [21]],
            ['title' => 'The Art of Computer Programming', 'category' => 'Computer Science', 'description' => 'Foundational series on algorithms and programming.', 'copies' => 2, 'author_index' => [22]],
            ['title' => 'The C Programming Language', 'category' => 'Computer Science', 'description' => 'The classic K&R guide to C.', 'copies' => 5, 'author_index' => [23]],
            ['title' => 'The C++ Programming Language', 'category' => 'Computer Science', 'description' => 'Reference and guide to C++ by its creator.', 'copies' => 3, 'author_index' => [24]],
            ['title' => 'The Java Programming Language', 'category' => 'Computer Science', 'description' => 'Introduction to Java by James Gosling.', 'copies' => 4, 'author_index' => [25]],
            ['title' => 'Java: The Complete Reference', 'category' => 'Computer Science', 'description' => 'Comprehensive Java reference and tutorial.', 'copies' => 3, 'author_index' => [26]],
            ['title' => 'Java How to Program', 'category' => 'Computer Science', 'description' => 'Introductory programming with Java.', 'copies' => 4, 'author_index' => [27]],
            ['title' => 'JavaScript: The Good Parts', 'category' => 'Computer Science', 'description' => 'The best ideas in JavaScript.', 'copies' => 4, 'author_index' => [28]],
            ['title' => 'Professional JavaScript for Web Developers', 'category' => 'Computer Science', 'description' => 'In-depth guide to JavaScript and web development.', 'copies' => 3, 'author_index' => [29]],
            ['title' => 'A Tale of Two Cities', 'category' => 'English', 'description' => 'A story set in London and Paris during the French Revolution.', 'copies' => 4, 'author_index' => [4]],
            ['title' => 'Oliver Twist', 'category' => 'English', 'description' => 'An orphan boy in Victorian London.', 'copies' => 4, 'author_index' => [4]],
            ['title' => 'David Copperfield', 'category' => 'English', 'description' => 'The personal history of David Copperfield.', 'copies' => 3, 'author_index' => [4]],
            ['title' => 'The Catcher in the Rye', 'category' => 'English', 'description' => 'A teenager\'s experiences in New York City.', 'copies' => 5, 'author_index' => [8]],
            ['title' => 'For Whom the Bell Tolls', 'category' => 'English', 'description' => 'An American in the Spanish Civil War.', 'copies' => 3, 'author_index' => [8]],
            ['title' => 'The Grapes of Wrath', 'category' => 'English', 'description' => 'A family fleeing the Dust Bowl for California.', 'copies' => 4, 'author_index' => [9]],
            ['title' => 'East of Eden', 'category' => 'English', 'description' => 'Two families in the Salinas Valley.', 'copies' => 3, 'author_index' => [9]],
            ['title' => 'The Adventures of Huckleberry Finn', 'category' => 'English', 'description' => 'A boy and a runaway slave on the Mississippi.', 'copies' => 5, 'author_index' => [7]],
            ['title' => 'To the Lighthouse', 'category' => 'English', 'description' => 'A family\'s visits to the Isle of Skye.', 'copies' => 3, 'author_index' => [10]],
            ['title' => 'The Importance of Being Earnest', 'category' => 'English', 'description' => 'A comedy of mistaken identity and manners.', 'copies' => 4, 'author_index' => [11]],
            ['title' => 'Through the Looking-Glass', 'category' => 'English', 'description' => 'Alice\'s second adventure in a mirror world.', 'copies' => 4, 'author_index' => [12]],
            ['title' => 'The Strange Case of Dr Jekyll and Mr Hyde', 'category' => 'English', 'description' => 'A man with two personalities.', 'copies' => 4, 'author_index' => [4]],
            ['title' => 'Brave New World', 'category' => 'English', 'description' => 'A dystopian future society.', 'copies' => 5, 'author_index' => [3]],
            ['title' => 'Lord of the Flies', 'category' => 'English', 'description' => 'Boys stranded on an island descend into savagery.', 'copies' => 5, 'author_index' => [2]],
            ['title' => 'The Hobbit', 'category' => 'English', 'description' => 'A hobbit\'s unexpected journey with dwarves.', 'copies' => 6, 'author_index' => [2]],
            ['title' => 'The Lord of the Rings', 'category' => 'English', 'description' => 'Epic fantasy trilogy of Middle-earth.', 'copies' => 4, 'author_index' => [2]],
            ['title' => 'Moby-Dick', 'category' => 'English', 'description' => 'Captain Ahab\'s quest for the white whale.', 'copies' => 3, 'author_index' => [7]],
            ['title' => 'The Scarlet Letter', 'category' => 'English', 'description' => 'A woman condemned for adultery in Puritan Boston.', 'copies' => 4, 'author_index' => [5]],
            ['title' => 'Little Women', 'category' => 'English', 'description' => 'Four sisters growing up during the Civil War.', 'copies' => 5, 'author_index' => [5]],
            ['title' => 'Robinson Crusoe', 'category' => 'English', 'description' => 'A castaway who spends years on a remote island.', 'copies' => 4, 'author_index' => [4]],
            ['title' => 'Gulliver\'s Travels', 'category' => 'English', 'description' => 'Satirical travels to imaginary lands.', 'copies' => 4, 'author_index' => [11]],
            ['title' => 'Treasure Island', 'category' => 'English', 'description' => 'Young Jim and the search for buried treasure.', 'copies' => 5, 'author_index' => [7]],
            ['title' => 'The Time Machine', 'category' => 'English', 'description' => 'A scientist travels to the far future.', 'copies' => 4, 'author_index' => [3]],
            ['title' => 'The War of the Worlds', 'category' => 'English', 'description' => 'Martian invasion of Earth.', 'copies' => 4, 'author_index' => [3]],
            ['title' => 'The Invisible Man', 'category' => 'English', 'description' => 'A scientist who makes himself invisible.', 'copies' => 3, 'author_index' => [3]],
            ['title' => 'Heart of Darkness', 'category' => 'English', 'description' => 'A journey into the Congo and human nature.', 'copies' => 4, 'author_index' => [8]],
            ['title' => 'The Turn of the Screw', 'category' => 'English', 'description' => 'A ghost story about a governess and two children.', 'copies' => 3, 'author_index' => [10]],
            ['title' => 'Rebecca', 'category' => 'English', 'description' => 'A young bride and the shadow of her husband\'s first wife.', 'copies' => 4, 'author_index' => [5]],
            ['title' => 'Wide Sargasso Sea', 'category' => 'English', 'description' => 'Prequel to Jane Eyre from Bertha\'s perspective.', 'copies' => 3, 'author_index' => [6]],
            ['title' => 'The Bell Jar', 'category' => 'English', 'description' => 'A woman\'s descent into mental illness.', 'copies' => 4, 'author_index' => [9]],
            ['title' => 'Beloved', 'category' => 'English', 'description' => 'A former slave and the ghost of her daughter.', 'copies' => 4, 'author_index' => [0]],
            ['title' => 'The Color Purple', 'category' => 'English', 'description' => 'Letters of Celie in the American South.', 'copies' => 5, 'author_index' => [1]],
            ['title' => 'Slaughterhouse-Five', 'category' => 'English', 'description' => 'A soldier unstuck in time.', 'copies' => 4, 'author_index' => [8]],
            ['title' => 'Catch-22', 'category' => 'English', 'description' => 'Absurdity of war and bureaucracy.', 'copies' => 5, 'author_index' => [9]],
            ['title' => 'One Hundred Years of Solitude', 'category' => 'English', 'description' => 'The Buendia family over seven generations.', 'copies' => 3, 'author_index' => [2]],
            ['title' => 'The Handmaid\'s Tale', 'category' => 'English', 'description' => 'Dystopian tale of a theocratic America.', 'copies' => 5, 'author_index' => [5]],
            ['title' => 'The Kite Runner', 'category' => 'English', 'description' => 'Friendship and redemption in Afghanistan.', 'copies' => 5, 'author_index' => [1]],
            ['title' => 'Life of Pi', 'category' => 'English', 'description' => 'A boy and a tiger adrift in the Pacific.', 'copies' => 5, 'author_index' => [4]],
            ['title' => 'The Book Thief', 'category' => 'English', 'description' => 'A girl who steals books in Nazi Germany.', 'copies' => 4, 'author_index' => [6]],
            ['title' => 'The Alchemist', 'category' => 'English', 'description' => 'A shepherd\'s journey to find his treasure.', 'copies' => 6, 'author_index' => [7]],
            ['title' => 'The Da Vinci Code', 'category' => 'English', 'description' => 'A murder mystery involving secret societies.', 'copies' => 4, 'author_index' => [8]],
            ['title' => 'Harry Potter and the Philosopher\'s Stone', 'category' => 'English', 'description' => 'A young wizard discovers his destiny.', 'copies' => 6, 'author_index' => [9]],
            ['title' => 'Harry Potter and the Chamber of Secrets', 'category' => 'English', 'description' => 'Harry\'s second year at Hogwarts.', 'copies' => 5, 'author_index' => [9]],
            ['title' => 'Harry Potter and the Prisoner of Azkaban', 'category' => 'English', 'description' => 'Harry learns about his past.', 'copies' => 5, 'author_index' => [9]],
            ['title' => 'The Chronicles of Narnia', 'category' => 'English', 'description' => 'Children discover a magical wardrobe.', 'copies' => 5, 'author_index' => [10]],
            ['title' => 'The Lion the Witch and the Wardrobe', 'category' => 'English', 'description' => 'First Narnia tale of four children.', 'copies' => 6, 'author_index' => [10]],
            ['title' => 'A Wrinkle in Time', 'category' => 'English', 'description' => 'Children travel through space and time.', 'copies' => 4, 'author_index' => [11]],
            ['title' => 'The Giver', 'category' => 'English', 'description' => 'A boy learns the truth about his community.', 'copies' => 5, 'author_index' => [12]],
            ['title' => 'The Hunger Games', 'category' => 'English', 'description' => 'Teens fight to the death in a dystopian America.', 'copies' => 5, 'author_index' => [13]],
            ['title' => 'Divergent', 'category' => 'English', 'description' => 'A society divided into factions.', 'copies' => 4, 'author_index' => [14]],
            ['title' => 'The Fault in Our Stars', 'category' => 'English', 'description' => 'Two teens with cancer fall in love.', 'copies' => 5, 'author_index' => [0]],
            ['title' => 'Where the Crawdads Sing', 'category' => 'English', 'description' => 'A girl raised in the marshes of North Carolina.', 'copies' => 4, 'author_index' => [1]],
            ['title' => 'Educated', 'category' => 'English', 'description' => 'A memoir of growing up without school.', 'copies' => 4, 'author_index' => [2]],
            ['title' => 'Becoming', 'category' => 'English', 'description' => 'Memoir of Michelle Obama.', 'copies' => 5, 'author_index' => [3]],
            ['title' => 'Design Patterns', 'category' => 'Computer Science', 'description' => 'Elements of reusable object-oriented software.', 'copies' => 4, 'author_index' => [17]],
            ['title' => 'Python Crash Course', 'category' => 'Computer Science', 'description' => 'Hands-on introduction to Python programming.', 'copies' => 5, 'author_index' => [18]],
            ['title' => 'Automate the Boring Stuff with Python', 'category' => 'Computer Science', 'description' => 'Practical Python for everyday tasks.', 'copies' => 4, 'author_index' => [19]],
            ['title' => 'Fluent Python', 'category' => 'Computer Science', 'description' => 'Clear and effective Python programming.', 'copies' => 3, 'author_index' => [20]],
            ['title' => 'Effective Java', 'category' => 'Computer Science', 'description' => 'Best practices for the Java platform.', 'copies' => 4, 'author_index' => [21]],
            ['title' => 'JavaScript and jQuery', 'category' => 'Computer Science', 'description' => 'Interactive front-end web development.', 'copies' => 4, 'author_index' => [28]],
            ['title' => 'Eloquent JavaScript', 'category' => 'Computer Science', 'description' => 'A modern introduction to programming.', 'copies' => 5, 'author_index' => [29]],
            ['title' => 'You Don\'t Know JS', 'category' => 'Computer Science', 'description' => 'Deep dive into JavaScript mechanics.', 'copies' => 3, 'author_index' => [28]],
            ['title' => 'Learning PHP MySQL and JavaScript', 'category' => 'Computer Science', 'description' => 'Full-stack web development fundamentals.', 'copies' => 4, 'author_index' => [27]],
            ['title' => 'PHP and MySQL Web Development', 'category' => 'Computer Science', 'description' => 'Building dynamic web applications.', 'copies' => 3, 'author_index' => [26]],
            ['title' => 'Laravel: Up and Running', 'category' => 'Computer Science', 'description' => 'A framework for building web applications.', 'copies' => 4, 'author_index' => [25]],
            ['title' => 'Vue.js: Up and Running', 'category' => 'Computer Science', 'description' => 'Building reactive web interfaces.', 'copies' => 3, 'author_index' => [24]],
            ['title' => 'React: Up and Running', 'category' => 'Computer Science', 'description' => 'Building modern user interfaces.', 'copies' => 4, 'author_index' => [23]],
            ['title' => 'Node.js Design Patterns', 'category' => 'Computer Science', 'description' => 'Server-side JavaScript patterns.', 'copies' => 3, 'author_index' => [22]],
            ['title' => 'Database System Concepts', 'category' => 'Computer Science', 'description' => 'Foundations of database systems.', 'copies' => 3, 'author_index' => [16]],
            ['title' => 'SQL in 10 Minutes', 'category' => 'Computer Science', 'description' => 'Quick practical SQL lessons.', 'copies' => 5, 'author_index' => [21]],
            ['title' => 'Learning SQL', 'category' => 'Computer Science', 'description' => 'Master SQL fundamentals.', 'copies' => 4, 'author_index' => [20]],
            ['title' => 'Operating System Concepts', 'category' => 'Computer Science', 'description' => 'Core concepts of operating systems.', 'copies' => 3, 'author_index' => [16]],
            ['title' => 'Computer Networks', 'category' => 'Computer Science', 'description' => 'A top-down approach to networking.', 'copies' => 3, 'author_index' => [17]],
            ['title' => 'Artificial Intelligence: A Modern Approach', 'category' => 'Computer Science', 'description' => 'Comprehensive AI textbook.', 'copies' => 2, 'author_index' => [18]],
            ['title' => 'Deep Learning', 'category' => 'Computer Science', 'description' => 'Neural networks and deep learning.', 'copies' => 3, 'author_index' => [19]],
            ['title' => 'Hands-On Machine Learning', 'category' => 'Computer Science', 'description' => 'Machine learning with Scikit-Learn and TensorFlow.', 'copies' => 4, 'author_index' => [20]],
            ['title' => 'Clean Architecture', 'category' => 'Computer Science', 'description' => 'Craftsmanship of software design.', 'copies' => 3, 'author_index' => [15]],
            ['title' => 'The DevOps Handbook', 'category' => 'Computer Science', 'description' => 'How to create world-class agility and reliability.', 'copies' => 3, 'author_index' => [21]],
            ['title' => 'Continuous Delivery', 'category' => 'Computer Science', 'description' => 'Reliable software releases through build and test.', 'copies' => 3, 'author_index' => [17]],
            ['title' => 'Site Reliability Engineering', 'category' => 'Computer Science', 'description' => 'How Google runs production systems.', 'copies' => 2, 'author_index' => [22]],
            ['title' => 'The Mythical Man-Month', 'category' => 'Computer Science', 'description' => 'Essays on software engineering.', 'copies' => 4, 'author_index' => [23]],
            ['title' => 'Structure and Interpretation of Computer Programs', 'category' => 'Computer Science', 'description' => 'Classic CS textbook using Scheme.', 'copies' => 2, 'author_index' => [24]],
            ['title' => 'Code', 'category' => 'Computer Science', 'description' => 'The hidden language of computer hardware and software.', 'copies' => 4, 'author_index' => [25]],
            ['title' => 'The Self-Taught Programmer', 'category' => 'Computer Science', 'description' => 'The definitive guide to programming professionally.', 'copies' => 5, 'author_index' => [26]],
            ['title' => 'Cracking the Coding Interview', 'category' => 'Computer Science', 'description' => 'Technical interview preparation.', 'copies' => 5, 'author_index' => [27]],
            ['title' => 'Grokking Algorithms', 'category' => 'Computer Science', 'description' => 'An illustrated guide for programmers.', 'copies' => 5, 'author_index' => [28]],
            ['title' => 'Algorithms Unlocked', 'category' => 'Computer Science', 'description' => 'A gentle introduction to algorithms.', 'copies' => 4, 'author_index' => [16]],
            ['title' => 'Data Structures and Algorithms in Python', 'category' => 'Computer Science', 'description' => 'Implementation and analysis in Python.', 'copies' => 4, 'author_index' => [29]],
            ['title' => 'The Go Programming Language', 'category' => 'Computer Science', 'description' => 'The official guide to Go.', 'copies' => 4, 'author_index' => [23]],
            ['title' => 'Rust Programming Language', 'category' => 'Computer Science', 'description' => 'The official Rust book.', 'copies' => 4, 'author_index' => [24]],
            ['title' => 'Programming Rust', 'category' => 'Computer Science', 'description' => 'Fast safe systems development.', 'copies' => 3, 'author_index' => [25]],
            ['title' => 'TypeScript Handbook', 'category' => 'Computer Science', 'description' => 'Typed JavaScript for large applications.', 'copies' => 4, 'author_index' => [26]],
            ['title' => 'Pro Git', 'category' => 'Computer Science', 'description' => 'The complete guide to Git version control.', 'copies' => 5, 'author_index' => [27]],
            ['title' => 'Docker Deep Dive', 'category' => 'Computer Science', 'description' => 'Containerization from basics to orchestration.', 'copies' => 4, 'author_index' => [28]],
            ['title' => 'Kubernetes in Action', 'category' => 'Computer Science', 'description' => 'Deploying and managing containerized apps.', 'copies' => 3, 'author_index' => [29]],
            ['title' => 'HTTP: The Definitive Guide', 'category' => 'Computer Science', 'description' => 'The web\'s foundation protocol.', 'copies' => 3, 'author_index' => [18]],
            ['title' => 'RESTful Web APIs', 'category' => 'Computer Science', 'description' => 'Design and implementation of web APIs.', 'copies' => 4, 'author_index' => [19]],
            ['title' => 'GraphQL in Action', 'category' => 'Computer Science', 'description' => 'Query language for APIs.', 'copies' => 3, 'author_index' => [20]],
            ['title' => 'Security Engineering', 'category' => 'Computer Science', 'description' => 'A guide to building dependable systems.', 'copies' => 2, 'author_index' => [21]],
            ['title' => 'Web Security for Developers', 'category' => 'Computer Science', 'description' => 'Real threats and defensive techniques.', 'copies' => 4, 'author_index' => [22]],
            ['title' => 'The Cathedral and the Bazaar', 'category' => 'Computer Science', 'description' => 'Reflections on Linux and open source.', 'copies' => 4, 'author_index' => [23]],
            ['title' => 'Working Effectively with Legacy Code', 'category' => 'Computer Science', 'description' => 'Strategies for modifying existing code.', 'copies' => 3, 'author_index' => [20]],
            ['title' => 'Release It!', 'category' => 'Computer Science', 'description' => 'Design and deploy production-ready software.', 'copies' => 3, 'author_index' => [21]],
            ['title' => 'The Midnight Library', 'category' => 'English', 'description' => 'A library between life and death with infinite books.', 'copies' => 5, 'author_index' => [0]],
            ['title' => 'Project Hail Mary', 'category' => 'English', 'description' => 'An astronaut alone on a desperate mission.', 'copies' => 4, 'author_index' => [1]],
            ['title' => 'Where the Red Fern Grows', 'category' => 'English', 'description' => 'A boy and his two hunting dogs.', 'copies' => 4, 'author_index' => [2]],
            ['title' => 'The Secret Garden', 'category' => 'English', 'description' => 'A girl discovers a hidden garden.', 'copies' => 5, 'author_index' => [5]],
            ['title' => 'Anne of Green Gables', 'category' => 'English', 'description' => 'An orphan girl on Prince Edward Island.', 'copies' => 5, 'author_index' => [6]],
            ['title' => 'The Wind in the Willows', 'category' => 'English', 'description' => 'Adventures of Mole, Rat, and Toad.', 'copies' => 4, 'author_index' => [7]],
            ['title' => 'Charlotte\'s Web', 'category' => 'English', 'description' => 'A pig and a spider in a barn.', 'copies' => 6, 'author_index' => [8]],
            ['title' => 'Bridge to Terabithia', 'category' => 'English', 'description' => 'Two friends create a magical kingdom.', 'copies' => 4, 'author_index' => [9]],
            ['title' => 'Tuck Everlasting', 'category' => 'English', 'description' => 'A family that will never grow old.', 'copies' => 4, 'author_index' => [10]],
            ['title' => 'Number the Stars', 'category' => 'English', 'description' => 'A girl helps her Jewish friend escape.', 'copies' => 4, 'author_index' => [11]],
            ['title' => 'The Westing Game', 'category' => 'English', 'description' => 'Sixteen heirs compete for a fortune.', 'copies' => 3, 'author_index' => [12]],
            ['title' => 'Hatchet', 'category' => 'English', 'description' => 'A boy survives in the Canadian wilderness.', 'copies' => 5, 'author_index' => [13]],
            ['title' => 'Swift Programming', 'category' => 'Computer Science', 'description' => 'The big nerd ranch guide to Swift.', 'copies' => 4, 'author_index' => [14]],
            ['title' => 'iOS Programming Fundamentals', 'category' => 'Computer Science', 'description' => 'Swift and the iOS SDK.', 'copies' => 3, 'author_index' => [15]],
            ['title' => 'Android Programming', 'category' => 'Computer Science', 'description' => 'The big nerd ranch guide to Android.', 'copies' => 4, 'author_index' => [16]],
        ];
    }
}
