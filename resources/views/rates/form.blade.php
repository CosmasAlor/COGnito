<div class="mb-4">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $rate->name ?? 'Juba Mall Daily Exchange Rate') }}" required readonly>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>



<div class="mb-3">
    <label for="rate" class="form-label">Buying Price</label>
    <input type="number" step="0.0001" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate" value="{{ old('rate', $rate->rate ?? '') }}" required>
    @error('rate')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


<div class="mb-3">
    <label for="sellingrate" class="form-label">Selling Price</label>
    <input type="number" step="0.0001" class="form-control @error('sellingrate') is-invalid @enderror" id="sellingrate" name="sellingrate" value="{{ old('sellingrate', $rate->sellingrate ?? '') }}" required>
    @error('sellingrate')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>



<div class="mb-3">
    <label for="currency" class="form-label">Currency</label>
    <input type="text" class="form-control @error('currency') is-invalid @enderror" id="currency" name="currency" value="{{ old('currency', $rate->currency ?? 'SSP') }}" required maxlength="3" readonly>
    @error('currency')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<?php
$currentDate = date('m/d/Y');

?>
<div class="mb-3">
    <label for="effective_date" class="form-label">Date</label>
    <input type="date" class="form-control @error('effective_date') is-invalid @enderror" id="effective_date" name="effective_date" value="{{ old('effective_date', $rate->effective_date ?? '<?php echo $currentDate; ?>') }}" required>
    @error('effective_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>



<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea readonly class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $rate->description ?? 'Daily Operation Exchnage Rate, SSP Against USD') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($rate) ? $rate->is_active : true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>