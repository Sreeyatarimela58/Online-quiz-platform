**ONLINE QUIZ PLATFORM **

1. Responsive Layout
   Mobile-friendly design using Bootstrap's grid system (container, row, col)
   Centered card-based UI for questions/results (d-flex, justify-content-center, align-items-      center)

2. Visual Components
    For Login Page (showLoginPage()):
    Gradient background card (background: linear-gradient(...))
    Icon-based header (graduation cap icon)
    Styled form inputs with:
      Rounded corners (border-radius)
      Focus effects (box-shadow on :focus)
      Animated submit button with hover effects (transform: translateY(-2px))

For Exam Page:
  Exam header with:
    Exam title
    Question counter (e.g., "Question 1 of 10")
    Live timer (with warning animation when time is low)
Progress bar showing completion status

Option cards:
  Interactive radio buttons (hidden, styled via label)
  Hover/selection effects (color change to pink)
Navigation buttons (Previous/Next or Finish)

For Results Page:
  Visual result summary:
    Trophy (pass) or cross (fail) icon
    Score percentage with progress bar

Detailed breakdown:
  Color-coded questions (green/red for correct/incorrect)
  Correct answers highlighted
  
"Back to Home" button

3. Interactive Elements

Styled radio buttons (hidden <input> + custom <label> design)
Animated buttons (hover effects, transitions)
Real-time timer (JavaScript countdown with visual warnings)
Dynamic progress bar (updates width via PHP/JS)

4. Typography & Icons
  Font Awesome icons used throughout:
    Clock (timer)
    Arrow (navigation)
    Trophy/cross (results)
Bold headings with consistent spacing
Readable text contrast (white text on gradient cards)

5. Accessibility & Usability
     
Semantic HTML (forms, headings, labels)
Clear visual feedback:
  Selected options turn pink
  Time warnings pulse red
  Correct/incorrect answers color-coded
  Responsive touch targets (large clickable areas for options)

