1. Comment system                               [DONE]
2. Assignment       - edit                      V1.1.0
                    - callback                  V1.1.0
                    - submit assignment         [DONE]
                    - doing assignment          [DONE]
3. Account                                      [DONE]
4. Statistics                                   [TODO]
5. Superuser validation                         [DONE]

//
6. Test results                                 [DONE]
7. Test skipped questions                       V1.1.1
8. Mark student attendance in schedules V1.1.0 [DONE]
9. Display feedback for when time consuming tasks occur such as upload

**Principal statistics**

#SCHEDULES
- Total schedules (link opens modal/other page with all schedules)
- Total done schedules (link opens modal/other page with all done schedules)
- Total unattended schedules (link opens modal/other page with all unattended schedules)
- Individual schedules by teachers ~ should be links where principal can click to view more details and comment


#ASSIGNMENTS
- Should be divided into two sections, recent and all (ideally)
- Total sent assignments
- Total graded assignments
- Specific teacher assignments
	- Link opens assignment and show submissions for that assignment

**Any assignment is a link that opens a modal that contains:**
- Students who have not submitted assignments
- Students who have submitted assignments

#Convert all sections to be get requests
- domain/?p=pageName (For only nav tabs) [DONE]
- domain/?p=pageName&tab=tabName (For nav tabs and tabs) [DONE]

#Add htaccess to filter get requests
- domain/pageName (For only nav tabs)
- domain/pageName/tabName (For nav tabs and tabs)


#FEEDBACK
These are the areas that need some form of feedback to let the user know something is happening. Suggested: toast that is shown until the process is done eg. "Creating classroom" or progress bar

##Areas that need the feedback (Progress bar or equivalent)
###Teacher section
- Create classroom [teacher]
- Create assignment - consider adding upload modal with progress similar to resources one
- Add schedule
- Create test
- Upload Resources [DONE]
- Update password
- Submit assignment
- Submit test
- Forgot -> reset password process (suggest creating a success page, and a fail page [I'll do it - AJ])

