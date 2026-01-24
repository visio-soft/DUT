# Project Handover & Overview

## Introduction
This platform is a comprehensive **Participation and Project Management System** designed to collect, evaluate, and manage user-generated suggestions for institutional projects. It allows administrators (e.g., municipalities, organizations) to open projects for public feedback, where users can submit ideas, vote, and discuss.

## 1. Admin Panel & Core Modules
The administration panel is built with **Filament PHP** and serves as the central control hub.

- **User & Role Management**: Manages access control, distinguishing between Administrators (full access) and Standard Users (citizens/employees).
- **Location Management**: Central database for hierarchical location data (Country -> City -> District -> Neighborhood).
- **Category Management**: Defines the thematic scope of projects (e.g., Infrastructure, Green Spaces, Education).
- **Project Structure**: Manages the core entities:
  - **Project Groups**: High-level collections of projects (e.g., "2024 Urban Renewal").
  - **Projects**: The actual initiatives open for feedback (e.g., "Central Park Renovation").
- **Suggestion Management**: The dashboard for reviewing, improving, approving, or rejecting user submissions.
- **Feedback Loop**: Management of Comments (moderation) and Surveys (creating polls).

## 2. Logical Data Hierarchy
The system follows a strict hierarchical structure to keep data organized:

1.  **Category**: The root classification (e.g., *Environment*).
2.  **Project Group**: A grouping under a category (e.g., *Coastal Cleanup Initiatives*).
3.  **Project**: The specific container for user input (e.g., *Beach A Cleanup Plan*).
    *   Defines parameters like **Budget Range**, **Timeline**, and **Voting Deadlines**.
4.  **Suggestion**: The end-user's proposal linked to a specific Project.
    *   Example: "Install more recycling bins at the north entrance."

**Flow:** Admin creates a **Project** -> User submits a **Suggestion** to that Project.

## 3. Location Infrastructure (Dependent Filtering)
The system uses a **Smart Dependent Filtering** mechanism for locations to ensure data accuracy and improve user experience. This applies to both the Admin Panel and User Forms.

- **Logic**: Parents dictate children. A user cannot select a specific location unit without selecting its parent first.
- **Workflow**:
    1.  Select **Country** (e.g., Turkey) -> *System loads linked Cities.*
    2.  Select **City** (e.g., Istanbul) -> *System loads linked Districts.*
    3.  Select **District** (e.g., Kadıköy) -> *System loads linked Neighborhoods.*
    4.  Select **Neighborhood** (e.g., Caferağa).
- **Benefit**: Prevents invalid location combinations (e.g., selecting a district that doesn't belong to the chosen city).

## 4. Key Engagement Features

### Voting
- Users can engage with suggestions via **Likes**.
- Each project has a `voting_ends_at` timestamp. Once passed, the voting system locks automatically.

### Comments & Discussion
- **Threaded Comments**: Users can reply to specific comments, creating organized discussion threads.
- **Moderation**: An approval system exists where admins can review comments before they become publicly visible, ensuring community guidelines are followed.

### Surveys
- Admins can attach **Surveys** to projects to gather specific structured data.
- Supports Multiple Choice and Text-based answers.

## 5. Technical Highlights
- **Media Library**: Integrated management for uploading cover photos and galleries for both Projects and Suggestions.
- **Multilingual Support**: The system is architected to support multiple languages: **English (en), Turkish (tr), French (fr), German (de), and Swedish (sv)**. This allows for easy localization of interface text and dynamic content.
- **Status Workflow**: Suggestions move through defined states (Pending -> Approved -> Rejected -> Completed) to track progress transparently.

## 6. User Panel vs. Admin Panel Relationship
The system operates with two distinct interfaces, each serving a specific role in the participation lifecycle.

### Panel Overview
*   **Admin Panel (`/admin`)**: The "Back-Office". Used by system operators to define structure, manage content, and oversee the workflow.
*   **User Panel (`/user`)**: The "Front-Office". Used by citizens or employees to engage with the system.

### Interaction Workflow
1.  **Definition (Admin)**: An Administrator creates a Project (e.g., "City Park Renovation"), sets the budget, location, and deadline.
2.  **Engagement (User)**: Users log in to the User Panel, see the new "City Park Renovation" project, and submit their specific ideas (Suggestions).
3.  **Moderation (Admin)**: The Administrator reviews incoming suggestions in the Admin Panel. They may approve, reject, or edit them for clarity.
4.  **Feedback (User)**: The user receives notifications about the status of their suggestion.
5.  **Community (User)**: Once approved, other users can see the suggestion, vote on it, and discuss it via comments.

## 7. Panel Switching & Navigation
Seamless navigation between the two panels is built-in to facilitate easy management.

*   **Header Buttons**: Navigation is not just limited to URL manipulation. Dedicated buttons in the header of each panel allow for instant switching:
    *   **Admin -> Site**: A "Go to Site" (Siteye Git) button in the Admin Panel header takes you to the public User Panel.
    *   **User -> Admin**: Authorized users will see a "Management Panel" button in the User Panel header to return to the backend.
*   **Access Control**: Standard users trying to access `/admin` will be denied access, ensuring security.

## 8. Translation & Localization
The system employs a dynamic **Translation Service** (`App\Services\TranslationService`) to handle multi-language content (e.g., Turkish <-> English).

*   **Automated Content Translation**: The system features an intelligent automated translation tool. When an admin creates content with a specific name or title (e.g., a Project named "Kadıköy Sahil Parkı"), the system **automatically translates** this dynamic content into supported languages in the background. This ensures that a project created in Turkish is immediately accessible to English-speaking users with an English title.
*   **Logic**: Content entered in the source language (e.g., Turkish) is translated via Google Translate API and stored in the `translations` table.
*   **Caching**: To optimize performance, translations are cached in the database, preventing repeated API calls for the same content.
*   **Usage**: Models like `Suggestion` and `Project` have helper methods (`getTranslatedAttribute`) to automatically retrieve the correct language version based on the user's current locale.

## 9. Surveys & Data Collection
Surveys are a powerful tool for gathering structured feedback on specific projects.

*   **Creation**: Admins create surveys within the Admin Panel (`SurveyResource`), linking them to specific Projects.
*   **Question Types**: Supports both **Open-Ended** (Text) and **Multiple Choice** questions.
*   **Analysis**: Admins can view individual responses and response counts directly in the panel.

## 10. Analytics & Feedback Visualization
The Admin Panel includes visual tools to help administrators make sense of user feedback quickly.

*   **Visual Charts**:
    *   **Project Suggestions Overview**: A chart visualizing the volume of suggestions per project, helping identify high-interest initiatives.
    *   **Survey Answer Distribution**: Graphic representation of multiple-choice survey answers, making public sentiment easy to gauge at a glance.
*   **Widgets**: Dashboard widgets provide key metrics like "Best Suggestions of Expired Projects", highlighting top-rated ideas that need attention after a voting period ends.
