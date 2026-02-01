# Learning module with conversational educational system - Moodle Plugin

This repository contains the **Moodle Activity Module** developed as the integration layer for the Bachelor's Engineering Thesis: **"Learning module with conversational educational system"**.

---

## Academic Context
* **University:** Warsaw University of Technology (Politechnika Warszawska)
* **Faculty:** Faculty of Mathematics and Information Science (MiNI)
* **Supervisor:** dr inż. Anna Wróblewska
* **Authors:** Anna Ostrowska, Gabriela Majstrak, Jan Opala

---

## Technical Overview
The plugin, identified as `mod_chatbot`, is a native Moodle Activity Module implemented in **PHP**. It serves as the primary interface for students and teachers within the Moodle LMS environment.

### Key Responsibilities:
* **Secure Embedding:** Utilizes a responsive IFrame to integrate the Next.js Frontend module directly into the course page.
* **Context Passing:** Securely transmits metadata (such as `course_id` and user authentication tokens) from Moodle to the system Backend to ensure context-aware RAG operations.
* **Instructor Controls:** Provides a standard Moodle configuration form (`mod_form.php`) allowing teachers to enable the assistant and configure its appearance per course.
* **Localization:** Supports multi-language interfaces (English and Polish) via Moodle's native language strings system.

---

## Installation
1. Pack the repository content into a `.zip` file.
2. Log in to Moodle as an Administrator.
3. Navigate to *Site administration* -> *Plugins* -> *Install plugins*.
4. Upload the package and follow the on-screen instructions to upgrade the database.

---

*Developed as a core technical component of the diploma process at Warsaw University of Technology.*
