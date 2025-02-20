<div class="shadow p-3 mb-3 bg-white rounded">
	<h6>Search</h6>
	<form>
	  <div class="rounded border-1 border p-2">
	  	 <label for="floatingSelect">Country</label>
	    <select name="residence_country" id="residence_country" aria-label="Default select example" class="form-select">
	      <option value="">I’m a resident of</option>
	      @foreach($residence_country as $key => $residence_country)
	        <option value="{{$residence_country->iso_2}}">{{$residence_country->country_name}}</option>
	      @endforeach
	    </select>
	   
	  </div>

	  <div class="rounded border-1 border p-2 mt-2 ">
	  	   <label for="floatingSelect">Visiting Country</label>
	    <select name="visiting_country" id="visiting_country" aria-label="Default select example" class="form-select">
	      <option value="">Visiting country</option>
	    </select>
	 
	  </div>
	</form>
</div>