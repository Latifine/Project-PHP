<div style="background-color: #f5f5f5; padding: 20px; font-family: Arial, sans-serif; color: #333;">
    <h2>Geachte heer/mevrouw, {{ $data['name'] }}</h2>

    <p>Bedankt voor uw bericht! We hebben het ontvangen en zullen zo spoedig mogelijk reageren.</p>

    <b style="color: #b22222;">Details van uw bericht:</b><br>
    <ul>
        <li>Naam: {{ $data['name'] }}</li>
        <li>E-mail: {{ $data['email'] }}</li>
        <li>Bericht: {{ $data['message'] }}</li>
    </ul>
    <hr style="border: 0; height: 1px; background: rgba(208,13,13,0.43); width: 100%;">

    <p>
        Met vriendelijke groet,<br>
        Het team van <b style="color: #b22222;">KVV Rauw</b><br>
    </p>

    <p>
        <b>Adres:</b> Blekestraat 40, 2400 Mol, België<br>
        <b>Telefoon:</b> 014 81 66 33
    </p>

    <div style="text-align: start; margin-top: 20px;">
        <a href="https://www.acoc.project-kvvrauw.be" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #b22222; text-decoration: none; border-radius: 5px;">
            Website
        </a>
    </div>
</div>
