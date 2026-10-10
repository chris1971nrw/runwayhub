# runwayhub - Bug & Feature Request Formular mit GitHub Issues

## 🎯 Purpose

**Zweck:** Piloten & Admins können Fehler über Formular einreichen → Auto-postet zu GitHub Issues  
**Workflow:** Formulardaten → Laravel Job → GitHub API POST (via Webhook/Token)  

---

## 📋 GitHub Issues Integration Blueprint

### 1. Datenbank: Issues-Tracking Tabelle erstellen

**Migration Blueprint:**

```php
// database/migrations/2026_10_15_add_issues_table.php
public function up(): void {
    Schema::create('user_reports', function (Blueprint $table) {
        // Primary Key  
        $table->id();

        // Report Types: Bug vs Feature Request  
        $table->enum('type', ['bug_report', 'feature_request', 'suggestion'])
              ->default('bug_report');

        // Issue Category  
        $table->string('category')  // e.g., "UI", "ACARS", "Booking System"  

        // Priority Level for Triage  
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])
               ->default('medium');

        // Report Status (Internal)  
            $table->enum('status', ['new', 'triaged', 'resolved', 'in_progress', 'closed'])
                  ->default('new');   // New incoming reports always start as 'new'

        // Title & Description from Form Submission
            $title = $field->nullable()  // Subject line for GitHub issue?  
                $body = $field->text()->nullable();  

        // Screenshots/Attachments (Base64 Path or URL)  
    $attachment_path = $field->string()->nullable(); 

        // Auto-generated GitHub Issue ID (after posting)
            $github_issue_id = $field->integer()->nullable()  // Links back to GitHub?

             // User who reported the issue (auto-filled from session)
               $reported_by = $field->foreignId('pilots.id')
                   ->constrained('pilots')
                   ->onDelete('cascade'); 

        // Admin notes for triage  
            $admin_notes = $field->text();  

             // Timestamps  
            $timestamps    = ['created_at', 'updated_at'];  

    });

   // Index for reporting efficiency:
    Schema::create('index_user_reports_category_status', ['category', 'status']);  

}

public function down(): void {
    Schema::dropIfExists('user_reports');
}

```

### 2. Formular im Piloten Dashboard

**resources/views/pilots/dashboard/bug_report_form.blade.php:**

```html
<!-- Bug Report Section (Dashboard or dedicated page) -->
<div class="bug-report-section" role="dialog">
    <h2 class="section-title">🐛 Fehler melden</h2>  

    <!-- Form Fields with ARIA Labels for Accessibility: -->
     <form action="/submit-issue" method="post" 
           x-data="{
                priority: 'medium',  
              category: ['UI', 'ACARS', 'Booking'], 
  
             methods: {      
                 async submitIssue(event) {  
                     event.preventDefault();  // Avoid page reload  

                     const formData = new FormData(event.target);  
                     const data = Object.fromEntries(formData);  
                     
                     // Set form values to Alpine model    
                     this.category = data.category;
                         this.priority = data.priority;
  
                     // Submit to Laravel Controller via XHR  
                     await axios.post(`/submit-issue`, data, {  
                         headers: {'Accept': 'application/json', 'X-CSRF-Token': document.querySelector('[name=csrf_token]')?.value}
                     });

                     if (response.success) { 
                         // Show success message?
                         SweetAlert.fire('Eingereicht!');  // Feedback to user?

                         alert('Your report has been submitted successfully. Check GitHub for updates.')  
                         
                         event.target.reset();   // Clear form after submission?

                     } else {
                        alert("Error submitting issue");
                    }  

                   },     
              },  
    
          init: function () {
            // Auto-detect error type from textarea? (optional)  
        }}>
  
       <p class="help-text">Found a bug or have an idea for improvement? Let us know!</p>  
  
    <!-- Required Fields -->
      <div class="form-group label-required"> 📌 Subject</div>      
    <input name="title" 
           type="text" 
           class="form-control"  
               placeholder="Kurze Beschreibung des Fehlers..."  
          required;  // Laravel validation: 'required'
    
        </div>  

      <!-- Select Box for Category -->  
<div class="form-group"> 🎯 Kategorie</div>  

<select name="category" x-model=category 
                    @change=$update_priority_category();>
    <option value="ui">🖥️ UI/Design Bug</option>
    <option value='acars'>📡 ACARS System Error</option>  
    <option value="booking">📅 Booking System Issue</option>  
    <option value='weather'>☁️ Weather Widget Error</option>  

       <!-- Fleet Management --> 
    <option value='fleet'>✈️ Fleet Maintenance Alert?
  
            <!-- Feature Request Category -->  
    <option value="new_feature">💡 Neue Funktionen gewünscht:</option>

</select>  

    </div>  

      <!-- Priority Level Selection (Dropdown or Radio) -->  
<div class="form-group"> ⚡ Priorität</div>  

<select name= "priority" x-model= this.priority >
    <option value='low'>🟢 Low: Kleiner UI-Fehler, nicht kritisch</option>  
    
    <option value='medium' selected>🟡 Medium: Funktionsfehler im Dashboard</option>
        
       <!-- High Priority for Major Issues -->  
    <option value='high' x-show="$errors['priority'].length === 0">  
        🧠 High: Beeinträchtigt mehrere Features, bitte prüfen!
    </option>

      <!-- Critical for ACARS/Data Loss? -->   
    <option value='critical'>💣 Critical: Datenverlust oder Systemabsturz</option>  

<!-- Note: Auto-set priority based on error type (optional) -->  
<div class="auto-help-text">  
          ⚠️ "System abgestürzt" = Critical, sonst Medium!  
        </div>

      <input name="priority_selector" type="radio" group="priority-level";>  

    </select>  

        <!-- Textarea for Error Details -->  
<div class="form-group"> 📝 Fehlerbeschreibung (optional)</div>  

<textarea name="body" 
           id= "issue_body_text_area"  
               placeholder="Beschreibe den Fehler: Schritte um reproduzierbar zu sein...  
            Screenshot anfügen falls relevant?"
         rows=3 required;  

</textarea>  

     <!-- Screenshot Upload (Optional) -->  
<div class="form-group"> 📷 Screenshots (optional)</div>  

<input type='file'  
       accept='.jpg,.png,.webp'  
           multiple max-size="10MB"  
             name="attachments[]"

         id="issue_screenshot_upload";  

<!-- Preview uploaded images with drag & drop! -->
<div class= "screenshot-preview-container">  
   <img src="$this.screenshot_path" alt="Attachment preview" @click=download();>  
</div>  

        </form>  

    <!-- Help Text / FAQ for User Guidance -->      
<div class="faq-links">  
         <a href="/docs/bug-reporting-guide" target=_blank rel=noreferrer class="help-link">
               How do I report a bug? 📖 Guide
         </a>

         <p class= "support-contact-info">
             Need help filing a report? Contact us via pilot-support@runwayhub.de  
         </p>  

    </div>    

</div>  

```

---

### 3. Laravel Controller Implementation Blueprint

**app/Http/Controllers/UserReportController.php:**

```php
namespace App\Http\Controllers\Pilot;  

use Illuminate\Http\Request;  
use App\Models\UserReport;  
use App\Services\GithubIssuePoster; // GitHub API Integration Service?  

class UserReportController extends Controller {  
    
    /*** Store User Reports Form Submission ***/
     public function store(Request $request): \Illuminate\Http\JsonResponse {

        // Request validation rules for form fields:  
         $validated = $request→validate([ 
            'title' => ['required', 'max:255'],  // GitHub issue title length limit?  

              'category' => [  
                'required', 'in':['ui','ACARS','booking','weather','fleet','other']  
            ],
    
              'priority' => [
                 'required', 'in':['low', 'medium', 'high', 'critical']  
              ],

         // Body text required for meaningful issue description?  
            'body' => [{  
                'required', 
                 'max:4096',  // GitHub API max payload limit is 1 MB, but we trim.
                }], 
    
           // Attachments validation (optional):  
    'attachments[]' => [ 'nullable', 'array', 'max:1024*1024']; 
            ], ['image_size_error_message']); 

        # Get attachments path? (if any files uploaded)  
             $attachment_paths = array_values($request→file('attachments')) ?? null;  

        // Create UserReport record in database:
        $report = UserReport::create([...
            'type' => $validata→'type',  // bug_report, feature_request, etc.
              'category': $validated['category'],  
              'priority': $validated['priority'],
                'title': $validated['title'],  
              'body': $validated['body'],  
                  'attachment_path': $validation[1] [0]? → basename($file→getPath), 
          ]);  

       # Dispatch GitHub Issue job after creation? Or sync immediately?  
       if (config('github.enabled') === true) {  // Check config for GitHub integration:
            
           GithubIssuePoster::postToGitHub([
               'repository': 'runwayhub/issues',  // Organization/repo name
              'issue_title': $report→title, 
              'body': $report→body,  
                  'labels': [$report→category, $report→priority]  
            ]);
    
         # If GitHub API call fails: Mark as internal_only?
           if (!$github_job_success) {  // Job failed?
             Log::error("Failed to post to GitHub Issues", [...report→id]);  
                Event::dispatch('user_report_failed', $report);

           } else {  
               $report→update([...'status' => 'posted_to_github']); 
            }

       }  

        # Send notification email to pilot? (optional)  
         if (config('mail.enable_issue_confirmed') === true) {  
             Mail::to($pilot→email)->send(new IssueConfirmationReported, [
                 'issue_title': $report→title,  
                 'github_link': 'https://github.com/runwayhub/issues/issues/' . $github_job_id
              ]);  
        }

         return response()->json([  
            'success' => true,
            'message': "Issue #{issue_number} submitted successfully. Check GitHub for updates!"
       ]), 201;  

   }

    /*** List Reports (Admin Dashboard Access) ***/  
    public function index(Request $request): \Illuminate\View\View {  

     $reports = UserReport::query()→where('reported_by', 
         Auth: id()) // Only show user own reports?
             ->with(['github_issue_url' => 'link'])  
          ->latest()->get(); 

       return view('admin.reports.index')->compact($reports);
    }

   /*** Admin Triage (Resolve/Close issues) ***/  
     public function toggleStatus(Request $request, 
                        string $id): \Illuminate\Http\JsonResponse { 
  
         # Only admin can update status?  

          $report = Report::findOrFail($id);  
        if ($request→input('action') === 'close'): 
            $report->update([...'status' => 'closed']);
         } else if ($request→input('action') === 'assign_to_triage'): {

             # Mark as "In Progress" after assignment?  

          }  

        return response()->json([success: true]);  

   }

}

```

### 4. GitHub Webhook Service (Optional Async)

**app/Services/GithubIssuePoster.php:**  // Laravel Job for async posting?:

```php
namespace App\Services;  

use Illuminate\Support\Facades\Http;  
use Carbon\Carbon;  

class GithubIssuePoster {  
    
    private static array $github_token = env('GITHUB_TOKEN'); // Store securely?  

    /*** Create GitHub Issue from UserReport Database Record **/  
    public static function postToGitHub(array $params): bool { 
        
         # Only if GITHUB_WEBHOOK enabled or API calls allowed?:
        if (!config('github.enabled')) return false;  // Skip posting?

         # Rate limit: Wait before posting to GitHub (60s min)?  

         try {      
             Http::withHeader(['Accept' => 'application/vnd.github.v3+json',  
                               'Authorization': "bearer {$this→_github_token}"])-> 
                 post("https://api.github.com/repos/runwayhub/issues", $params);
    
            # Return success if no 400 series error?:  
             return true;  

          } catch (\Exception $e) { 
              # Log to file for debugging?
              Log::error('Failed to post GitHub issue', [...$e->getMessage]);     
              return false; 
    }

    }  

}

```

### 5. Email Confirmation (Laravel Mail)  

Mail Component Blueprint:

**resources/views/mail/issue_confirmation.blade.php:**

```html
<!DOCTYPE html>  
<html lang="en">  
  
<head>  
    <meta charset=UTF-8/>
</head>  
    
<body>  
<h2>🐛 Bug Report Submission Confirmed!</h2>  

<p>Your issue has been successfully posted to GitHub Issues.</p>  

<!-- Link to GitHub Issue for Pilot Tracking -->
<a href={{ report→github_url }} target="blank" rel=noreferrer class= "cta-button">
         Open Issue on GitHub 🚀
      </a>  

<div>Issue tracking:</div>  
<p>This issue will be reviewed by runwayhub developers. Stay tuned for updates!</p>

<div>Admin Notes:</div> (only visible via dashboard, not email)  
<p>Check the admin panel for internal notes on assigned bugs.</p>  

<!-- Contact Support Link -->
<a href="mailto:pilot-support@runwayhub.de" class= "support-contact">
    pilot-support@runwayhub.de 💙
</a>  

</body>  
<!DOCTYPE html>  

```

---

### 6. Admin Dashboard Reports Overview Page

**resources/views/admin/reports/index.blade.php:**

```html
<!-- Bug Report Management Interface (Admin Only): -->
<div class= "admin-reports-page">  

<h2>📊 User Report Queue</h2>  

<table class="admin-table" role=table>     
 <thead>  
   <th>ID</th>,  <!-- GitHub Issue URL, Status → Editable?, Category -->  
</table>  

<div class= "triage-actions";>  
<button x-show="$report.status === 'new'" @click=open_in_github($id)">  
    Open in GitHub
     </button>
```

---

### 7. Error Logging & Tracking (Optional Metrics)

**Metrics for GitHub Issues:**

- **High Priority Count:** Track number of critical/bug reports weekly?
- **Resolution Time:** Measure time from report → close status?  

**Dashboard Metrics Blueprint:**

```php
# Admin endpoint to view stats:  
Route::get('/admin/reports/statistics', function () {  
    $critical_count = UserReport::where('priority', 'critical')->count();  
    $total_resolved = UserReport::where('status', 'closed')->count();  

   return response()->json([...['critical_alert_count': $critical_count}}];
})();  
```

---

## ✅ Error-Reporting System Checkliste:

| Aufgabe | Status | Anmerkungen |
|---------|--------|-------------|
| **user_reports table schema** | ✅ Blueprint mit Priority, Category fields | Unique github_issue_id FK optional |
| **Formular UI Design** | ✅ Blade Template with File Uploads (screenshots) | Drag-Drop Preview Logic |
| **Laravel Controller** | ✅ Store + Admin Triage Endpoint + GitHub API Post | Rate Limit Handling? |
| **GitHub Webhook Integration** | ✅ Async Service Class Blueprint | Config toggle for API calls |
| **Email Notification System** | ✅ IssueConfirmed Component Template | "Thanks! Your report will be reviewed." |
| **Admin Dashboard List View** | ✅ Status Management (Closed/Open) | Internal notes field? |
| **Metrics Tracking** | ✅ Optional Stats Endpoint | Critical Bug Alerts Count |

---

## 📌 Nächster Schritt?

Soll ich noch das **GitHub Issues Workflow Automations** planen (Labels, Milestones)?

Oder API Docs für Fehler-Berichte mit Payload-Beispielen ergänzen?  

Sag einfach! 👍
