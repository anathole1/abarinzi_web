<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller {

    //  public function __construct() {
    //      // Apply permissions - view allowed for author/admin, delete requires specific permission
    //      $this->middleware('permission:view contact messages')->only(['index', 'show']);
    //      $this->middleware('permission:delete contact messages')->only(['destroy']);
    //       // toggleRead needs 'view' access at least, maybe a separate 'manage' permission?
    //       // For now, let's assume 'view' is enough to toggle read status.
    //      $this->middleware('permission:view contact messages')->only(['toggleRead']);
    //  }


    public function index() {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.content.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage) {
        // Mark as read when shown? Or use the toggle route.
        if (!$contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }
        return view('admin.content.contacts.show', compact('contactMessage'));
    }

     public function toggleRead(ContactMessage $contactMessage) {
         $contactMessage->update(['is_read' => !$contactMessage->is_read]);
         $status = $contactMessage->is_read ? 'read' : 'unread';
         return redirect()->back()->with('success', "Message marked as {$status}.");
     }


    public function destroy(ContactMessage $contactMessage) {
        $contactMessage->delete();
        return redirect()->route('admin.content.contacts.index')->with('success', 'Contact message deleted.');
    }
}