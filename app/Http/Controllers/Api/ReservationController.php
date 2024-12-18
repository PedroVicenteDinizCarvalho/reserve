<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return Reservation::with('table')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $conflict = $this->checkDateTime($request);

        if ($conflict) {
            return response()->json(['error' => 'A mesa não tem disponibilidade na data e horário informado'], 409);
        }

        return Reservation::create($request->all());
    }

    public function show(Reservation $reservation)
    {
        return $reservation->load('table');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $conflict = $this->checkDateTime($request);

        if ($conflict) {
            return response()->json(['error' => 'A mesa não tem disponibilidade na data e horário informado'], 409);
        }

        $reservation->update($request->all());

        return $reservation;
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json(['message' => 'Reserva cancelada']);
    }

    private function checkDateTime($request) {
        // Pega o dia da semana (0 = Sunday, 6 = Saturday)
        $dayOfWeek = \Carbon\Carbon::parse($request->date)->dayOfWeek;

        // Verifica a regra de horários
        if ($dayOfWeek >= 1 && $dayOfWeek <= 6) {
            $startHourLimit = '18:00';
            $endHourLimit = '23:59';

            if ($request->start_hour < $startHourLimit || $request->end_hour > $endHourLimit) {
                return true;
            }
        }

        $conflict = Reservation::where('table_id', $request->table_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_hour', [$request->start_hour, $request->end_hour])
                    ->orWhereBetween('end_hour', [$request->start_hour, $request->end_hour])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_hour', '<=', $request->start_hour)
                            ->where('end_hour', '>=', $request->end_hour);
                    });
            })
            ->exists();

        return $conflict;
    }
}
