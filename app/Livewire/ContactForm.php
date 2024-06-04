<?php

namespace App\Livewire;

use App\Enum\EmailType;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Mail\ContactMail;
use Illuminate\Mail\Mailables\Address;
use Mail;


class ContactForm extends Component
{
    // public properties
    #[Validate('required|min:3|max:50')]
    public $name;
    #[Validate('required|email')]
    public $email;
    #[Validate('required|min:10|max:500')]
    public $message;
    public $canSubmit = false;

    #[Layout('layouts.app', [
        'title' => "Contactformulier",
        'subtitle' => 'Neem contact met ons op.',
        'description' => 'Heb je een vraag of opmerking? Vul dan het contactformulier in.',
        'developer' => 'Gilles Bosmans'
    ])]

    // disable submit button until all fields are valid
    public function updated($propertyName, $propertyValue)
    {
        $this->canSubmit = false;
        $this->validateOnly($propertyName);
        if(count($this->getErrorBag()->all()) === 0)
            $this->canSubmit = true;
    }

    // send email
    public function sendEmail()
    {
        $this->validate();

        // send email
        $template = new ContactMail([

            'fromName' => 'Kvv Rauw Sport Mol - Info',

            'fromEmail' => EmailType::EMAIL_ADDRESS->value,
            'subject' => 'Kvv Rauw Sport Mol - Contact Form',
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $to = new Address($this->email, $this->name);
        Mail::to($to)->send($template);

        // show a success toast
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "<p class='font-bold mb-2'>Beste $this->name,</p>
                   <p>Bedankt voor je bericht.<br>We nemen zo snel mogelijk contact met je op.</p></p>",
        ]);

        // reset all public properties
        $this->reset();
    }
}
