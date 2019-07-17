<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\GroupRequest;
use Redirect;
use Sentinel;
use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {        
        //status = 0 : new , status = 1: show , status =2: checked, status = 3 :deleted 
        // Grab all the groups
        $contacts = Contact::where('status', '!=', 3)->get();

        // Show the page
        return view('admin.contact.index', compact('contacts'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return "";
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
       
        return "";
    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($cotnact)
    {
        
        // Show the page
        return "";
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function show($contact_id, Request $request)
    {
        $contact = Contact::find($contact_id);
        $contact->status = 1;
        $contact->save();         
        return view('admin.contact.show', compact('contact'));
    }

    /**
     * Delete the given group.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {
        try {
            // Get group information
            $role = Sentinel::findRoleById($id);

            // Delete the group
            $role->delete();

            // Redirect to the group management page
            return Redirect::route('groups.index')->with('success', trans('groups/message.success.delete'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('groups.index')->with('error', trans('groups/message.group_not_found', compact('id')));
        }
    }

}
