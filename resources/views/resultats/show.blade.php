@extends('layout.app')
@section('title', 'Détail du résultat')
@section('content')
	<div class="mx-auto max-w-3xl">
		<div class="mb-6 flex items-center justify-between">
			<h1 class="text-3xl font-bold">Résultat #{{ $resultat->id }}</h1>
			<a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('resultats.edit', $resultat) }}">Modifier</a>
		</div>
		<div class="panel rounded-2xl bg-white p-6 space-y-3">
			<p><strong>Patient :</strong> {{ $resultat->patient?->prenom }} {{ $resultat->patient?->nom }}</p>
			<p><strong>Analyse :</strong> {{ $resultat->analyse?->nom }}</p>
			<p><strong>Valeur :</strong> {{ $resultat->valeur ?? '—' }} {{ $resultat->unite }}</p>
			<p><strong>Statut :</strong> <span class="badge">{{ $resultat->statut }}</span></p>
			<p><strong>Médecin :</strong> {{ $resultat->medecin ? 'Dr ' . $resultat->medecin->prenom . ' ' . $resultat->medecin->nom : '—' }}</p>
			<p><strong>Date :</strong> {{ $resultat->date_resultat?->format('d/m/Y') ?: '—' }}</p>
			<p><strong>Remarques :</strong> {{ $resultat->remarques ?: '—' }}</p>
		</div>

		<div class="panel mt-6 rounded-2xl bg-white p-6">
			<h2 class="mb-2 text-xl font-semibold">Envoi WhatsApp</h2>
			@if($resultat->whatsapp_sent_at)
				<p class="text-sm text-emerald-700">Envoyé le {{ $resultat->whatsapp_sent_at->format('d/m/Y à H:i') }}.</p>
			@elseif($resultat->whatsapp_error)
				<p class="mb-3 text-sm text-red-700">{{ $resultat->whatsapp_error }}</p>
			@endif
			@if($resultat->statut !== 'en_attente')
				<form class="mt-4" method="POST" action="{{ route('resultats.whatsapp', $resultat) }}">
					@csrf
					<button class="btn-primary rounded-xl px-4 py-2" type="submit">Envoyer par WhatsApp</button>
				</form>
			@else
				<p class="text-sm text-slate-500">Le résultat doit être validé avant son envoi.</p>
			@endif
		</div>
	</div>
@endsection
