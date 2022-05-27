<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Students;

class StudentsComponent extends Component
{
    public $student_id, $name, $email, $phone, $student_edit_id, $student_delete_id;
    public $student_id_edit, $name_edit, $email_edit, $phone_edit;
    public $view_student_id, $view_student_name, $view_student_email, $view_student_phone;


    // Input fields on update validation
    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'student_id' => 'required|unique:students,student_id,' . $this->student_edit_id . '', //validation with ignoring own data
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);
    }

    public function storeStudentData()
    {
        // on form submit validation
        $this->validate([
            'student_id' => 'required|unique:students,student_id', //students = table name
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);

        // Add Student Data
        $student = new Students();
        $student->student_id = $this->student_id;
        $student->name = $this->name;
        $student->email = $this->email;
        $student->phone = $this->phone;

        $student->save();

        session()->flash('message', 'Student Student has been added successfully!');

        $this->student_id = null;
        $this->name = null;
        $this->email = null;
        $this->phone = null;

        // For Hide Modal after add student success
        $this->dispatchBrowserEvent('close-modal');
    }

    public function resetInputs()
    {
        $this->student_id = null;
        $this->name = null;
        $this->email = null;
        $this->phone = null;
        $this->student_edit_id = null;
    }

    public function editStudentData()
    {
        // on form submit validation
        $this->validate([
            'student_id_edit' => 'required|unique:students,student_id,' . $this->student_edit_id . '', //validation with ignoring own data
            'name_edit' => 'required',
            'email_edit' => 'required|email',
            'phone_edit' => 'required|numeric',
        ]);

        // Edit Student Data
        $student = Students::where('id', $this->student_edit_id)->first();
        $student->student_id = $this->student_id_edit;
        $student->name = $this->name_edit;
        $student->email = $this->email_edit;
        $student->phone = $this->phone_edit;

        $student->save();

        session()->flash('message', 'Student has been Updated successfully!');

        // For Hide Modal after Edit student success
        $this->dispatchBrowserEvent('close-modal');

        $this->student_id_edit = null;
        $this->name_edit = null;
        $this->email_edit = null;
        $this->phone_edit = null;
        $this->student_edit_id = null;
    }
    public function editStudents($id)
    {
        $student = Students::where('id', $id)->first();

        $this->student_edit_id = $student->student_id;
        $this->student_id_edit = $student->student_id;
        $this->name_edit = $student->name;
        $this->email_edit = $student->email;
        $this->phone_edit = $student->phone;

        $this->dispatchBrowserEvent('show-edit-student-modal');
    }

    public function showAddStudents()
    {
        $this->student_id = null;
        $this->name = null;
        $this->email = null;
        $this->phone = null;
        $this->student_edit_id = null;
        $this->dispatchBrowserEvent('show-add-student-modal');
    }

    // Delete Confirmation
    public function deleteConfirmation($id)
    {
        $this->student_delete_id = $id; //student id
        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    public function deleteStudentData()
    {
        $student = Students::where('id', $this->student_delete_id)->first();
        $student->delete();

        session()->flash('message', 'Student has been deleted successfully');
        $this->dispatchBrowserEvent('close-modal');

        $this->student_delete_id = '';
    }

    public function cancel()
    {
        $this->student_delete_id = '';
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewStudentDetails($id)
    {
        $student = Students::where('id', $id)->first();

        $this->view_student_id = $student->student_id;
        $this->view_student_name = $student->name;
        $this->view_student_email = $student->email;
        $this->view_student_phone = $student->phone;

        $this->dispatchBrowserEvent('show-view-student-modal');
    }

    public function closeViewStudentModal()
    {
        $this->view_student_id = null;
        $this->view_student_name = null;
        $this->view_student_email = null;
        $this->view_student_phone = null;
    }

    public function render()
    {
        // Get All Students Data
        $students = Students::all();
        return view('livewire.students-component', ['students' => $students])->layout('livewire.layouts.base');
    }
}
