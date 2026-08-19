@extends('frontend.layouts.master')
@push('title') {{$faqs->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$faqs->meta_keywords}}">
<meta name="description" content="{{$faqs->meta_description}}">@endpush 
<style type="text/css">
	strong {
  color: #dc3545;
}
</style>
@section('content')
<section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Faqs</span>
          </div>
        </div>
      </div>
    </section>
     <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0"> {{$faqs->title}}</h1>
         
        </div>
      </div>
    </section>
<section class="section_widget p-4">
 <div class="container mb-4">
      <div class="row">
	   
	          <div class="secondheader shadow-sm p-3 mb-5 bg-white rounded py-4">
             <ul class="nav nav-tabs product_detail d-flex" style="border-bottom:none">
                <li class="active"><a href="#Russia">Russia</a></li>               
                <li><a href="#Kazakhstan">Kazakhstan</a></li>
                <li><a href="#Uzbekistan">Uzbekistan</a></li>         
                <li><a href="#Kyrgyzstan">Kyrgyzstan</a></li>
                <li><a href="#Armenia">Armenia</a></li>
                <li><a href="#Georgia">Georgia</a></li>
                <li><a href="#Ukraine">Ukraine</a></li>      
            </ul>
          </div>
        <div class="col-md-12">
            <!-- detail -->
            <div id="Russia" class="tab-pane fade in active">
            	 <div class="col-md-12">
            	 	<h6>FAQ of Russia</h6> <p>Frequently Asked Questions: Russia Tour, Visa to Russia, Travel to Russia, Russia Destinations</p>
            	 </div>
            	 <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				         1. Where is Russia situated?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Located in the continent of Europe, Russia covers 16,377,742 square kilometers of land and 720,500 square kilometers of water, making it the 1st largest nation in the world with a total area of 17,098,242 square kilometers. Russia shares land borders with 14 countries: Belarus,     Lithuania, Latvia, Estonia, Finland, Norway, Poland, Kazakhstan, Ukraine, North Korea, China, Mongolia, Azerbaijan, Georgia.</p>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				         2. Why should one visit Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>There are 6 reasons why should one visit Russia:</p>
			                <ul class="list-unstyled list-tick">
			                  <li> The Diversity of Russia’s Geography</li>
			                  <li>Russia’s Cultural Life</li>
			                  <li>Russian History</li>
			                  <li>Quick Historical Numbers</li>
			                  <li>Russian Language</li>
			                  <li>Russian People</li>
			                  <li>Russian Weather</li>
			                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				          3. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
				      <div class="card-body">
				       <ul class="list-unstyled list-tick">
		                  <li>Etihad has flights from Bangalore to Moscow with a stoppage at Abu Dhabi.</li>
		                  <li>Air India has flights from Bangalore to Moscow with a stoppage at Delhi.</li>
		                </ul>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="4">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four" aria-expanded="false" aria-controls="four">
				         4. What is the best season to visit Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="four" class="collapse" aria-labelledby="4" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>May till September is usually the most preferred <a href="https://www.dookinternational.com/about/russia" target="_blank"><strong>time to visit Russia</strong></a> as the weather is pleasant. June is usually warm in Russia. But if you don't mind cold temperatures and are fond of
                           winter sports, Nov-Dec and Jan-Feb can be awesome with sports like skiing, ice-skating and snowboarding.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="5">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#five" aria-expanded="false" aria-controls="five">
				        5. What are the most interesting places in Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="five" class="collapse" aria-labelledby="5" data-parent="#accordionExample">
				      <div class="card-body">
				       <ul class="list-unstyled list-tick">
		                  <li>The most visited destinations in Russia are Moscow and Saint Petersburg, the current and former capitals of the country. Recognized as World Cities, they feature such world-renowned museums as the Tretyakov Gallery and the Hermitage, famous theaters like <a
		                      href="https://www.dookinternational.com/blog/bolshoi-theater-moscow/" target="_blank"><strong>Bolshoi</strong></a> and Mariinsky, ornate churches like
		                    <a href="https://www.dookinternational.com/blog/saint-basils-cathedral-moscow/" target="_blank"><strong>Saint Basil's Cathedral</strong></a>, Cathedral of Christ the Saviour, Saint Isaac's Cathedral and Church of the Savior on Blood, impressive fortifications like <a
		                      href="https://www.dookinternational.com/blog/moscow-kremlin-russia/" target="_blank"><strong>The Kremlin</strong></a> and Peter and Paul Fortress, beautiful squares and streets like
		                    <a href="https://www.dookinternational.com/blog/red-square-moscow/" target="_blank"><strong>Red Square</strong></a>, Palace Square, Tverskaya Street and Nevsky Prospect. Rich palaces and parks are found in the former
		                    imperial residences in suburbs of Moscow (Kolomenskoye, Tsaritsyno) and <a href="https://www.dookinternational.com/blog/st-petersburg-a-city-with-rich-heritage/" target="_blank"><strong>St Petersburg</strong></a> (Peterhof, Strelna, Oranienbaum, Gatchina, Pavlovsk and Tsarskoye
		                    Selo). Moscow displays Soviet architecture at its best, along with modern skyscrapers, while St Petersburg, nicknamed Venice of the North, boasts of its classical architecture, many
		                    rivers, channels and bridges.
		                  </li>
		                  <li>Kazan, the capital of Tatrstan, shows a mix of Christian Russian and Muslim Tatar cultures. The city has registered a brand The Third Capital of Russia, though a number of other major cities compete for this status, including Novosibirsk, Yekaterinburg and Nizhny Novgorod.</li>
		                  <li>The warm subtropical Black Sea coast of Russia is the site for a number of popular sea resorts, like <a href="https://www.dookinternational.com/russia/moscow--sochi-and-saint-petersburg-9-nights-package/000989" target="_blank"><strong>Sochi</strong></a>, the follow-up host of
		                    the 2014 Winter Olympics. The mountains of the Northern Caucasus contain popular ski resorts such as
		                    <a href="https://www.dookinternational.com/blog/dombay-russia/" target="_blank"><strong>Dombay</strong></a>. The most famous natural destination in Russia is <a href="https://www.dookinternational.com/blog/lake-baikal-deepest-lake-in-the-world/" target="_blank"><strong>Lake
		                        Baikal</strong></a>, the Blue Eye of Siberia. This unique lake, the oldest and deepest in the world, has crystal-clear waters and is surrounded by taiga-covered mountains. Other
		                    popular natural destinations include <a href="https://www.dookinternational.com/blog/kamchatka-peninsula-a-remote-paradise-in-russia/" target="_blank"><strong>Kamchatka</strong></a> with its volcanoes and geysers, Karelia with its lakes and granite rocks, the <a
		                      href="https://www.dookinternational.com/blog/5-russian-destinations-beyond-moscow-and-st-petersburg-you-should-visit/" target="_blank"><strong>snowy Altai Mountains</strong></a>, and the wild
		                    steppes of Tyva.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="7">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seven" aria-expanded="false" aria-controls="seven">
				         6. What kind of tourists would like Russia as a holiday destination?
				        </button>
				      </h5>
				    </div>
				    <div id="seven" class="collapse" aria-labelledby="7" data-parent="#accordionExample">
				      <div class="card-body">
				       <ul class="list-unstyled list-tick">
		                  <li>Etihad has flights from Bangalore to Moscow with a stoppage at Abu Dhabi.</li>
		                  <li>Air India has flights from Bangalore to Moscow with a stoppage at Delhi.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="8">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eight" aria-expanded="false" aria-controls="eight">
				          7. How about availability of Indian food?
				        </button>
				      </h5>
				    </div>
				    <div id="eight" class="collapse" aria-labelledby="8" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Moscow and Saint-Petersburg, the most popular tourist destinations in Russia certainly has many authentic Indian restaurants that cater to Indian tourists and local patrons alike. Tourists on <a href="https://www.dookinternational.com/russia/moscow-and-st-petersburg-5-nights-package/0000110" target="_blank"><strong>Moscow</strong></a> and <a href="https://www.dookinternational.com/russia/saint-petersburg-3-nights-4-days-package/000987" target="_blank"><strong>Saint-Petersburg Tour Packages</strong></a> from India always have the option to eat authentic and delicious Indian meals.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="9">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine" aria-expanded="false" aria-controls="nine">
				        8. What are the hotel options we have in major cities of Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="nine" class="collapse" aria-labelledby="9" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The major cities of Russia - Moscow, Saint-Petersburg, Sochi, Kazan are abundant with the best in class 3, 4 and 5 star hotels. In Russia holiday packages tourists have several choices of good hotels in every price range. Several new large capacity luxurious hotels have recently  been built in Russia for the upcoming World Football Championship 2018 and will be available to tourists as well.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="10">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ten" aria-expanded="false" aria-controls="ten">
				          9. How can we entertain a MICE movement of 200 Pax? Does the Russian Airlines has this capacity and what about infrastructure on land?
				        </button>
				      </h5>
				    </div>
				    <div id="ten" class="collapse" aria-labelledby="10" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>For MICE tour package to Russia, tickets can be booked through Etihad or Air India or Aeroflot in order to move the majority of the 200 pax in a day. Please check with us for best options. Land infrastructure could accommodate &amp; entertain 200 pax in one day.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="11">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eleven" aria-expanded="false" aria-controls="eleven">
				         10. How about organizing a corporate Meet/Conference/Award Functions etc.
				        </button>
				      </h5>
				    </div>
				    <div id="eleven" class="collapse" aria-labelledby="11" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>We organize corporate events for MICE tour package to Moscow &amp; Saint-Petersburg of all sizes. Conference halls, banquets or large restaurants are all available to be booked for corporate conferences and award functions with the latest in light and sound options available. (We would need specific and detailed list of requirements in advance for the setup at the event and event flow schedule)</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="12">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twelve" aria-expanded="false" aria-controls="twelve">
				         11. Can we organize an Educational Tour for Students?
				        </button>
				      </h5>
				    </div>
				    <div id="twelve" class="collapse" aria-labelledby="12" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>We can organize all land services (transportation, accommodation in hotels, meals), but all professional and educational meetings (with professors etc) should be organized by participants themselves.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="13">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Thirteen" aria-expanded="false" aria-controls="Thirteen">
				          12. What is the visa process and how much time does it take?
				        </button>
				      </h5>
				    </div>
				    <div id="Thirteen" class="collapse" aria-labelledby="13" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>For Indian tourists the visa to Russia is through invitation from a Russian tour operator and the visa process usually takes about 1 week.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="14">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#forteen" aria-expanded="false" aria-controls="forteen">
				         13. I am from Bangladesh / Nepal, can we apply in India for Russian Tourist Visa?
				        </button>
				      </h5>
				    </div>
				    <div id="forteen" class="collapse" aria-labelledby="14" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Russian embassy is available in Bangladesh and Nepal.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="15">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fifteen" aria-expanded="false" aria-controls="fifteen">
				          14. How to get business visa for Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="fifteen" class="collapse" aria-labelledby="15" data-parent="#accordionExample">
				      <div class="card-body">
				       <ul class="list-unstyled list-tick">
		                  <li>In case a citizen of India wants to come to Russia for business purposes, to sign an agreement or participate in an auction, etc., he/she has to apply for a Business Visa. In order to receive such visa the inviting party has to register a Business Invitation.<strong>Read
		                      More: </strong>Russian Business Visa
		                  </li>
		                  <li>Such Invitation is registered in the FMS after a juridical person who was accredited there submits an appropriate application. Business Invitation will be drawn up after the investigation on whether an applicant had any violations during his/her previous visits to Russia. If
		                    there were any such applicant will refused in a Russian visa.
		                  </li>
		                  <strong>The business visa invitation letter can be obtained by either of these ways:</strong>
		                  <li>Russian Business Invitation Letter from organization (possible to get Online)</li>
		                  <li>Russian Invitation Letter from FMS</li>
		                  <li>Telex Russian Invitation</li>
		                  <li>The period of consideration is 12 working days (17 calendar days) for single and double-entry invitations and 17 working days (21 calendar days) for multi entry visas valid for 180 or 365 days.</li>
		                  <li>An invitation is a state approved document (issued on a form of the FMS). This document should be given to the citizen of India to present it in a consulate when applying for a visa. Business Visa for citizens of the India can be issued for a period from 1 month (30 days) to 5
		                    years. It can be single, double-entry or multi-entry.
		                  </li>
		                  <li>Please, note that according to Russian visa policy maximum stay in Russia without leave is 90 days in 180-period days.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="16">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sixteen" aria-expanded="false" aria-controls="sixteen">
				        15. What are the most popular clubs / Discos in Moscow City?
				        </button>
				      </h5>
				    </div>
				    <div id="sixteen" class="collapse" aria-labelledby="16" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>People of Russia are modern, fun-loving and like to socialize. Hence Moscow is sprawling with exciting clubs, discos and resto-bars. SOHO Rooms, Sixty, Looking Rooms, Coyote Ugly a few popular discotheques of Moscow.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="17">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seventeen" aria-expanded="false" aria-controls="seventeen">
				         16. What is the currency of Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="seventeen" class="collapse" aria-labelledby="17" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>The local currency of Russia is Russian Ruble. Each Indian Rupee is equivalent to 0,88 Russian Ruble and each USD is approximately 60 Russian Rubles.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="18">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eighteen" aria-expanded="false" aria-controls="eighteen">
				          17. What currency should we carry to Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="eighteen" class="collapse" aria-labelledby="18" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>You may carry US dollars or Euros which can be easily changed to local Rubles at one of the numerous currency exchanges in the cities.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="19">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nineteen" aria-expanded="false" aria-controls="nineteen">
				         18. Are there any specific rules at the immigration in Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="nineteen" class="collapse" aria-labelledby="19" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>A tourist should take passport and immigration card everywhere.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="20">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twenty" aria-expanded="false" aria-controls="twenty">
				          19. Is it advisable to travel to Russia in winter season?
				        </button>
				      </h5>
				    </div>
				    <div id="twenty" class="collapse" aria-labelledby="20" data-parent="#accordionExample">
				      <div class="card-body">
					      <p>The temperatures in Moscow could drop to minus 8 in November and minus 20 degrees in January. But if you like winter adventures, sports or just watching the snow covered landscape, winters in Moscow, <a href="https://www.dookinternational.com/blog/st-petersburg-in-winter-an-unmatched-beauty/" target="_blank"><strong>winters in St. Petersburg</strong></a>, Lake Baikal, Murmansk or Sochi would be awesome choice. If you have never experienced such cold weather, it
	                  is highly recommended that purchase the suitable winter clothing and make a trip to Russia; it’s guaranteed you'll never regret it, to say the least. Please contact us and we will help you decide the clothing required for your trip.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="21">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweetyone" aria-expanded="false" aria-controls="tweetyone">
				          20. Is the visa stamped on passport or it’s an EVISA?
				        </button>
				      </h5>
				    </div>
				    <div id="tweetyone" class="collapse" aria-labelledby="21" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Visas to Russia are stamped on the passport.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="22">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentytwo" aria-expanded="false" aria-controls="tweentytwo">
				         21. What is the frequency of Russian Airlines (SU) Ex Delhi?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentytwo" class="collapse" aria-labelledby="22" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Russian Airlines (SU) has daily flights to Moscow (SVO) ex-Delhi. Departure time is usually 01.25 a.m. and 05:50 a.m.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="23">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentythree" aria-expanded="false" aria-controls="tweentythree">
				          22. Do people speak Hindi / English in Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentythree" class="collapse" aria-labelledby="23" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>People of Russia are primarily Russian speaking people. People who live in the urban places speak English. The majority of people in Russia do not speak Hindi; hence it is advisable to move around the city only with an English speaking tour guide.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="24">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfour" aria-expanded="false" aria-controls="tweentyfour">
				         23. What's in it for families wanting to travel to Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfour" class="collapse" aria-labelledby="24" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>For families visiting Russia, there are numerous options for sight-seeing and exciting activities around the cities irrespective of their age-group.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="25">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfive" aria-expanded="false" aria-controls="tweentyfive">
				          24. Does the destination attract Honeymooners?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfive" class="collapse" aria-labelledby="25" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Saint-Petersburg and places around are considered extremely romantic due to the scenic beauty of the castles, lakes, Baltic seaside and spending evening time at a local nightclub can be memorable for any honeymoon couples. That makes Saint-Petersburg a fantastic honeymoon    destination similar to Europe but for a fraction of the cost!</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="26">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentysix" aria-expanded="false" aria-controls="tweentysix">
				          25. What other cuisines one must try in Russia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentysix" class="collapse" aria-labelledby="26" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>There are numerous local restaurants that serve local Caucasus, Ukrainian, Chinese dishes. Local restaurants service numerous options for non-vegetarians and some vegetarian dishes are delightful too! Besides that you may always want to taste some red or black caviar, which is a   traditional Russian delicacy.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="27">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyseven" aria-expanded="false" aria-controls="tweentyseven">
				         26. What is there for the shopaholic?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyseven" class="collapse" aria-labelledby="27" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Irrespective of the size of your pockets there are numerous malls and markets that are eye candy for the shopaholic. Whether you're looking for an international luxury brand – Moscow and Saint-Petersburg have the best shopping options for you. There is also a famous Izmailovskiy    Souvenir Market and traditional trade Arbat street, full of clothing and souvenir shops.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="28">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight" aria-expanded="false" aria-controls="tweentyeight">
				         27. Can we get a Hindi speaking guide?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight" class="collapse" aria-labelledby="28" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>There are no Hindi speaking guides in Russia.</p>
				      </div>
				    </div>
				  </div>
				</div>
             </div> 
             <hr> 
             <!-- itinery -->
             <div id="Kazakhstan" class="tab-pane fade">
                <div class="col-md-12">
                	<h6>FAQ of Kazakhstan</h6> 
                	<p>Frequently Asked Questions: Kazakhstan Tour, Visa to Kazakhstan, Travel to Kazakhstan, Kazakhstan Destinations</p>
                </div>
                <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne1">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
				         1. Where is Kazakhstan situated ?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne1" class="collapse show" aria-labelledby="headingOne1" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Kazakhstan is located in central Asia, south-east of Europe and borders with Russia, China, Kyrgyzstan, Uzbekistan, and Turkmenistan, and also adjoins a large part of the Caspian Sea. In the olden days of Silk Route it was one of the main trade hubs. Almaty is the modern Silk   Route trade hub. Kazakhstan gained its freedom from former USSR in 1991 along with other CIS countries that were part of USSR.</p>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo2">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
				         2. Why should one visit Kazakhstan.
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo2" data-parent="#accordionExample">
				      <div class="card-body">
				           <p>Kazakhstan is very rich in cultures, has abundant breathtaking landscapes. The new and ultra modern city of Astana is the new capital of Kazakhstan whereas Almaty is the former capital and often dubbed the cultural capital of Kazakhstan. It exhibits indomitable spirit from wild horses that symbolize nomadic freedom to the rapidly growing vibrant cities. Typically, Almaty Tour Package from India would cover all the essence of Kazakhstan and its richness of all sorts. Anyone who has visited Kazakhstan has had their notion shattered. Unlike what many perceive a "stan" country to be, Kazakhstan is a very modern, liberal, tourist friendly and free nation! In recent times Almaty tours have emerged as a European Holiday Package in many ways but for a fraction of the price.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree3">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree3" aria-expanded="false" aria-controls="collapseThree3">
				          3. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree3" class="collapse" aria-labelledby="headingThree3" data-parent="#accordionExample">
				      <div class="card-body">
				       <ul class="list-unstyled list-tick">
		                  <li>Air Arabia has flights from Bangalore to Almaty with a stoppage at Sharjah.</li>
		                  <li>Alternatively, you may reach Delhi and take the Air Astana morning flight from Delhi to Almaty.</li>
		                </ul>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="41">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four1" aria-expanded="false" aria-controls="four1">
				         4. What is the best season to visit Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="four1" class="collapse" aria-labelledby="41" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>March till September is usually the most preferred <a href="https://www.dookinternational.com/about/kazakhstan" target="_blank"><strong>time to visit Kazakhstan</strong></a> as the weather is pleasant. June is usually warm in Kazakhstan and hot in the desert region. But if you don't mind cold temperatures and are fond of winter sports, Nov-Dec and Jan-Feb can be awesome with sports like <a href="https://www.dookinternational.com/blog/sunkar-international-ski-jumping-complex-in-almaty/" target="_blank"><strong>skiing</strong></a>, ice-skating and snowboarding. In summer's Samruk is on the paraglider's map too.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="51">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#five1" aria-expanded="false" aria-controls="five1">
				        5. What are the most interesting places in Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="five1" class="collapse" aria-labelledby="51" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li>The most popular destination in Kazakhstan is Almaty. <a href="https://www.dookinternational.com/kazakhstan-tour-packages" target="_blank"><strong>Almaty tour packages</strong></a> are becoming increasingly popular with international tourists due to its richness of nature and
		                    culture as well as its nearness to exciting and beautiful locations like <a href="https://www.dookinternational.com/blog/chimbulak-ski-resort-in-almaty/" target="_blank"><strong>Shymbulak</strong></a>,
		                    Kapchagay, <a href="https://www.dookinternational.com/blog/the-charyn-canyon-in-kazakhstan/" target="_blank"><strong>Charyn National Park</strong></a> (canyons) and lakes like <a href="https://www.dookinternational.com/blog/big-almaty-lake/" target="_blank"><strong>Big Almaty
		                        Lake</strong></a>, Kolsai, Lake Kaindy etc.
		                  </li>
		                  <li><a href="https://www.dookinternational.com/kazakhstan/astana-tour-2-nights-3-days-package/0000128" target="_blank"><strong>Astana</strong></a>, the modern capital city of Kazakhstan is often said to resemble Dubai and is known for its beautiful skyline and urban life.</li>
		                  <li>For those interested in archeology and ancient Kazakhstan, there is a petro glyph site in Semirechye, to the north-west Almaty.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="6">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#six" aria-expanded="false" aria-controls="six">
				          6. What kind of tourists would like Kazakhstan as a holiday destination?
				        </button>
				      </h5>
				    </div>
				    <div id="six" class="collapse" aria-labelledby="6" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Tourists taking Kazakhstan tour packages are primarily from the following groups:</p>
		                <ul class="list-unstyled list-tick">
		                  <li>Corporate Incentive Tourist Groups</li>
		                  <li>Business Tourists</li>
		                  <li>Cultural Tourists: Families and groups wanting to explore Almaty, which is said to be the ultimate melting pot of cultures with people from over a hundred ethnicities.</li>
		                  <li>Leisure Tourists: Summer time mountain adventure as well as some of the best arenas for winter sports. Many tourists looking for Europe tour package travel and enjoy Kazakhstan.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="81">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eight1" aria-expanded="false" aria-controls="eight1">
				          7. How about availability of Indian food?
				        </button>
				      </h5>
				    </div>
				    <div id="eight" class="collapse1" aria-labelledby="81" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Moscow and Saint-Petersburg, the most popular tourist destinations in Russia certainly has many authentic Indian restaurants that cater to Indian tourists and local patrons alike. Tourists on <a href="https://www.dookinternational.com/russia/moscow-and-st-petersburg-5-nights-package/0000110" target="_blank"><strong>Moscow</strong></a> and <a href="https://www.dookinternational.com/russia/saint-petersburg-3-nights-4-days-package/000987" target="_blank"><strong>Saint-Petersburg Tour Packages</strong></a> from India always have the option to eat authentic and delicious Indian meals.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="91">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine1" aria-expanded="false" aria-controls="nine1">
				        8. What are the hotel options we have in major cities of Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="nine1" class="collapse" aria-labelledby="91" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The major cities of Kazakhstan, namely Almaty and Astana are abundant with the best in class 3, 4 and 5 star hotels. In Kazakhstan holiday packages tourists have several choices of good hotels in every price range. Several new large capacity luxurious hotels have recently been     built in Astana for the upcoming Expo 2017 and will be available to tourists as well.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="101">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ten1" aria-expanded="false" aria-controls="ten1">
				          9. How can we entertain a MICE movement of 200 Pax ? Does the Kazakhstan Airways has this capacity?
				        </button>
				      </h5>
				    </div>
				    <div id="ten1" class="collapse" aria-labelledby="101" data-parent="#accordionExample">
				      <div class="card-body">
				     <p><strong>Option 1</strong>: For <strong>MICE tour package to Kazakhstan</strong>, tickets can be booked through Air Astana, Fly Dubai and Air Arabia in order to move the majority of the 200 pax in a day.</p><p><strong>Option 2</strong>: A more convenient way to do it is by flying groups of 50-60 daily by Air Astana fights from Delhi to Almaty and organizing the event on the day when all 200pax are in Almaty (on day 3 or 4).</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eleven1" aria-expanded="false" aria-controls="eleven1">
				         10. How about organizing a corporate meet / conference / Award functions etc.
				        </button>
				      </h5>
				    </div>
				    <div id="eleven1" class="collapse" aria-labelledby="111" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>We organize corporate events for MICE tour package to Almaty of all sizes. Conference halls, banquets or large restaurants are all available to be booked for corporate conferences and award functions with the latest in light and sound options available. (We would need specific and detailed list of requirements in advance for the setup at the event.)</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="121">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twelve1" aria-expanded="false" aria-controls="twelve1">
				        11. Can we organize an educational tour for Students?
				        </button>
				      </h5>
				    </div>
				    <div id="twelve1" class="collapse" aria-labelledby="121" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Yes, We have organized some <strong>educational tours for students</strong>. Besides many indian students study in kazak medical universities.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="131">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Thirteen1" aria-expanded="false" aria-controls="Thirteen1">
				          12. What is the visa process and how much time does it take?
				        </button>
				      </h5>
				    </div>
				    <div id="Thirteen1" class="collapse" aria-labelledby="131" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>For Indian tourists the <a href="https://www.dookinternational.com/visa/kazakhstan" target="_blank"><strong>visa to Kazakhstan</strong></a> is through invitation from a <strong>Kazakh tour operator</strong> and the visa process usually takes about 20 working days.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="141">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#forteen1" aria-expanded="false" aria-controls="forteen1">
				         13. I am from Bangladesh / Nepal, can we apply in India for Uzbekistan Tourist Visa?
				         </button>
				      </h5>
				    </div>
				    <div id="forteen1" class="collapse" aria-labelledby="141" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Since there are no Kazakhstan embassies in Bangladesh and Nepal, citizens of these countries will need to apply for Visa at the Kazakhstan embassy in New Delhi.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="151">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fifteen1" aria-expanded="false" aria-controls="fifteen1">
				          14. How to get business visa for Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="fifteen1" class="collapse" aria-labelledby="151" data-parent="#accordionExample">
				      <div class="card-body">
				        <ul class="list-unstyled list-tick">
		                  <li><strong>Single Entry Business Visas</strong>: A letter with a request to issue a visa addressed to the Consular Section of the Embassy of Kazakhstan indicating the purpose of trip, your contact in Kazakhstan, the dates of your planned trip and places to be visited.</li>
		                  <li><strong>Double/Triple/Multiple Entry Business Visas</strong>: A copy of the invitation letter from a host organization in Kazakhstan is needed. This letter must be submitted by the host organization in Kazakhstan to the Department of Consular Services of the Ministry of Foreign
		                    Affairs of the Republic of Kazakhstan.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="161">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sixteen1" aria-expanded="false" aria-controls="sixteen1">
				       15. What are the most popular clubs / Discos in Almaty City?
				        </button>
				      </h5>
				    </div>
				    <div id="sixteen1" class="collapse" aria-labelledby="161" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>People of Kazakhstan are modern, fun-loving and like to socialize. Hence Almaty is sprawling with exciting clubs, discos and resto-bars. Esparanza, Metro Club, Papa Bar, Hit Bar a few popular discotheques of Almaty.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="171">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seventeen1" aria-expanded="false" aria-controls="seventeen1">
				         16. What is the currency of Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="seventeen1" class="collapse" aria-labelledby="171" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The local currency of Kazakhstan is Tinge.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="181">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eighteen1" aria-expanded="false" aria-controls="eighteen1">
				          17. What currency should we carry to Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="eighteen1" class="collapse" aria-labelledby="181" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>You may carry US dollars or Euros which can be easily changed to local Tinge at one of the numerous currency exchanges in the cities.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="191">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nineteen1" aria-expanded="false" aria-controls="nineteen1">
				         18. Are there any specific rules at the immigration in Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="nineteen1" class="collapse" aria-labelledby="191" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>If a tourist wishes to spend more than five days in Kazakhstan, he/she will need to apply for extension of stay by visiting the immigration office. This rule is critical and must be adhered to.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="201">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twenty1" aria-expanded="false" aria-controls="twenty1">
				          19. Is it advisable to travel to Kazakhstan in winter season?
				        </button>
				      </h5>
				    </div>
				    <div id="twenty1" class="collapse" aria-labelledby="201" data-parent="#accordionExample">
				      <div class="card-body">
					     <p>The temperatures in Almaty drop to minus 8 in November and minus 20 degrees in January. But if you like winter adventures, sports or just watching the snow covered landscape, winters in Almaty would be awesome choice. If you have never experienced such cold weather, it is highly recommended to purchase the suitable winter clothing and make a trip to Almaty; its guaranteed you'll never regret it, to say the least.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="211">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweetyone1" aria-expanded="false" aria-controls="tweetyone1">
				          20. Is the visa stamped on passport or it’s an EVISA?
				        </button>
				      </h5>
				    </div>
				    <div id="tweetyone1" class="collapse" aria-labelledby="211" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Visas to Kazakhstan are stamped on the passport.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="221">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentytwo1" aria-expanded="false" aria-controls="tweentytwo1">
				         21. What is the frequency of Air Astana Ex Delhi?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentytwo1" class="collapse" aria-labelledby="221" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Air Astana has daily flights to Almaty (ALA) ex-Delhi. Departure time is usually 11:30 a.m.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="231">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentythree1" aria-expanded="false" aria-controls="tweentythree1">
				          22. Do people speak Hindi / English in Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentythree1" class="collapse" aria-labelledby="231" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>People of Kazakhstan are primarily Kazakh and Russian speaking people. Only a section of the modern youth living in the urban places speak English. Even though Hindi movies and songs have been popular in Kazakhstan they do not understand or speak Hindi. Hence it is advisable to   move around the city only with an English speaking tour guide.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="241">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfour1" aria-expanded="false" aria-controls="tweentyfour1">
				         23. What's in it for families wanting to travel to Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfour1" class="collapse" aria-labelledby="241" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>For families visiting Kazakhstan, there are numerous options for sight-seeing and exciting activities around the cities irrespective of their age-group. Apart from the sight seeing, a visit to Ziloyni Bazar takes you back in time a thousand years in the days when Children are  particularly fond of cable car rides, dolphin show, falcon show and a visit to Rahat Chocolate Factory outlet simply light up their curious eyes.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="251">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfive1" aria-expanded="false" aria-controls="tweentyfive1">
				          24. Does the destination attract Honeymooners?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfive1" class="collapse" aria-labelledby="251" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Almaty and places around are considered extremely romantic due to the scenic beauty of the mountains, lakes flaura and fauna and spending evening time at a local nightclub can be memorable for any honeymoon couples. That makes Almaty a fantastic honeymoon destination similar to  Europe but for a fraction of the cost!</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="261">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentysix1" aria-expanded="false" aria-controls="tweentysix1">
				          25. What other cuisines one must try in Kazakhstan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentysix1" class="collapse" aria-labelledby="261" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>There are numerous local restaurants that serve local Kazakh, Indian, Chinese and Continental dishes. Local restaurants serve numerous options for non-vegetarians and some vegetarian dishes are delightful too!</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="271">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyseven1" aria-expanded="false" aria-controls="tweentyseven1">
				         26. What is there for the shopaholic?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyseven1" class="collapse" aria-labelledby="271" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>Irrespective of the size of your pockets there's numerous malls and markets that are eye candy for the shopaholic. Whether you're looking for an item that once moved through the Silk Route or an international luxury brand - Almaty has the best shopping options for you. Amongst        conventional markets, Zilyoni Bazar or Green Market is the most popular market and merely a visit can be such a learning experience!</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="281">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight1" aria-expanded="false" aria-controls="tweentyeight1">
				         27. Can we get a Hindi speaking guide?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight1" class="collapse" aria-labelledby="281" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>A Hindi-speaking guide can be arranged. <a href="https://www.dookinternational.com/contact-us" target="_blank"><strong>Contact us</strong></a> well in advance and we can arrange one.</p>
				      </div>
				    </div>
				  </div>
				</div>
            </div>
            <hr>
            <!-- inclusion -->
            <div id="Uzbekistan" class="tab-pane fade mt-4 pt-4">
                 <div class="col-md-12">
                 	<h6>FAQ of Uzbekistan</h6>
                  <p>Frequently Asked Questions: Uzbekistan/Tashkent Tour, Visa to Tashkent, Travel to Uzbekistan, Uzbekistan Destinations</p>
              	</div>
              <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne11">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne11" aria-expanded="true" aria-controls="collapseOne11">
				         1. Where it’s Situated?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne11" class="collapse show" aria-labelledby="headingOne11" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>Uzbekistan is situated in central Asia between the Amu Darya and Syr Darya Rivers, the Aral Sea, and the slopes of the Tien Shan Mountains. It is bounded by Kazakhstan in the north and northwest, Kyrgyzstan and Tajikistan in the east and southeast, Turkmenistan in the southwest,and Afghanistan in the south.</p>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo21">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo21" aria-expanded="false" aria-controls="collapseTwo21">
				         2. Why should one visit Uzbekistan.
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo21" class="collapse" aria-labelledby="headingTwo21" data-parent="#accordionExample">
				      <div class="card-body">
				            <ul class="list-unstyled list-tick">
		                  <li>There are many reasons for Uzbekistan to be loved for. A few of them like great architecture in Tashkent, Samarkand, and Bukhara &amp; Khiva. Weather of Uzbekistan is the second loved thing about the country. Worth to mention that Uzbekistan is the 4th happiest country in the
		                    world despite being a developing nation. Hospitality levels of Uzbeks are some you will not find anywhere in the world.
		                  </li>
		                  <li>To mention, about 120000 Indian tourists visited Uzbekistan since 2009 and it tells the story of growth itself. <a href="https://www.dookinternational.com/uzbekistan-tashkent-tour-packages" target="_blank"><strong>Tour Packages to Uzbekistan</strong></a> have become quite
		                    popular for Indian Travelers for it being pocket friendly and quite short traveling time i.e. 2.5 Hrs flight Ex- Delhi.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree31">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree31" aria-expanded="false" aria-controls="collapseThree31">
				          3. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree31" class="collapse" aria-labelledby="headingThree31" data-parent="#accordionExample">
				      <div class="card-body">
 						<p>You must take a flight down to New Delhi, as Uzbekistan Airways flies 06 Times a week Ex Delhi and 03 Times a Week Ex Amritsar direct to Tashkent, Uzbekistan.</p>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="411">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four11" aria-expanded="false" aria-controls="four11">
				         4. What is the best season to visit Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="four11" class="collapse" aria-labelledby="411" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>All seasons are best seasons for an Indian Traveler. You may enjoy snowfall from November - March and Sunny weather from April - October. We have heard people comparing it with Switzerland during winter season and with Austria in summers.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="511">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#five11" aria-expanded="false" aria-controls="five11">
				        5. What are the most interesting places in Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="five11" class="collapse" aria-labelledby="511" data-parent="#accordionExample">
				      <div class="card-body">
				           <p>Tashkent is the metropolitan city of Uzbekistan and has all to offer for all kind of tourists. Samarkand takes you back to History with its mind blowing architecture. <strong>Tashkent to Samarkand in bullet train</strong> lets you compare this country with Japan. <a                    href="https://www.dookinternational.com/blog/bukhara-the-holiest-city-of-central-asia/" target="_blank"><strong>BUKAHRA</strong></a> &amp; <a href="https://www.dookinternational.com/blog/khiva-a-historical-city-in-uzbekistan/" target="_blank"><strong>KHIVA</strong></a> are             masterpieces which hold you back to 300BC. The amazing happy people of this country can knock off even extreme anxiety of anyone.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="61">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#six1" aria-expanded="false" aria-controls="six1">
				          6. The destination is for what kind of tourists?
				        </button>
				      </h5>
				    </div>
				    <div id="six1" class="collapse" aria-labelledby="61" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>The destination suits to Families, MICE, Adventure tourists &amp; Honeymooners. It’s even highly recommended as wedding destination.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="811">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eight11" aria-expanded="false" aria-controls="eight11">
				          7. What are the ways to reach Historical City Samarkand?
				        </button>
				      </h5>
				    </div>
				    <div id="eight11" class="collapse" aria-labelledby="811" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The best way to visit is by AFROSIYOB Bullet Train. The distance is about 300 KMS and it takes 02 Hrs to cover it from Tashkent - Samarkand. Alternatively, one may opt for regular train like NASAF and it takes 3.5 Hours to cover the destination. By road is another beautiful option   to reach to Samarkand and it takes 3.5 Hours to reach the destination. One may choose to come back same day to Tashkent or stay overnight in Smarkand.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="911">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine11" aria-expanded="false" aria-controls="nine11">
				        8. How about availability of Indian food?
				        </button>
				      </h5>
				    </div>
				    <div id="nine11" class="collapse" aria-labelledby="911" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>About 10 <a href="https://www.dookinternational.com/blog/indian-restaurants-in-tashkent/" target="_blank"><strong>Indian restaurants</strong></a> are operating to cater the big volume of Indian tourists. In case of bigger MICE movement, Indian food can be arranged in Hotel itself.  </p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1011">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ten11" aria-expanded="false" aria-controls="ten11">
				        9. How to reach Bukhara and whats its historical importance?
				        </button>
				      </h5>
				    </div>
				    <div id="ten11" class="collapse" aria-labelledby="1011" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>To reach Bukhara, one may opt to take one hour flight from Tashkent or can go even by Bullet Train which takes 3.5 Hours. An alternate suggested route is <a href="https://www.dookinternational.com/blog/train-journey-in-uzbekistan-a-glance/" target="_blank"><strong>Tashkent -
                      Samarkand by train</strong></a> and overnight in Samarkand and later in the morning by Taxi / Coach to Bukhara on a 03 Hours drive.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eleven11" aria-expanded="false" aria-controls="eleven11">
				       10. What are the hotel options we have in major cities of Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="eleven11" class="collapse" aria-labelledby="1111" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>We have many hotel options in each city of Uzbekistan. Tashkent has a variety of options such as Hotel Hyatt, Hotel City Palace, Hotel Radisson, Hotel Ramada, Hotel Le Grand Plaza,Uzbekistan Hotel and Hotel Lotte etc. Samarkand, Bukhara and Khiva also have many hotels to attract including beautiful boutique properties. Summarizing, one can say Uzbekistan has good infrastructure to accommodate all kinds of tourists.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1211">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twelve11" aria-expanded="false" aria-controls="twelve11">
				       11. How can we entertain a MICE movement of 200 Pax? Does the Uzbekistan Airways has this capacity and what about infrastructure on land?
				        </button>
				      </h5>
				    </div>
				    <div id="twelve11" class="collapse" aria-labelledby="1211" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Yes, 245 People can be taken in one flight and we have hotels where inventory is as big as 350 rooms in one hotel only. Coaches are of high quality like Mercedes, Golden Dragon, King Long, Hyundai etc. Some Indian restaurants can accommodate about 300 Pax in one go.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1311">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Thirteen11" aria-expanded="false" aria-controls="Thirteen11">
				          12. How about organizing a corporate meet / conference / Award functions etc?
				        </button>
				      </h5>
				    </div>
				    <div id="Thirteen11" class="collapse" aria-labelledby="1311" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>Yes, this we have been doing for a long period now. Big Indian corporate hosted many of their events in the past. Upon request pictures of past events can be shared, too.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1411">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#forteen11" aria-expanded="false" aria-controls="forteen11">
				        13. Can we organize an educational tour for Students?
				         </button>
				      </h5>
				    </div>
				    <div id="forteen11" class="collapse" aria-labelledby="1411" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>Yes, we have been getting regular requests for such <a href="https://www.dookinternational.com/uzbekistan/tashkent-special-tour-for-students-4-nights-package/0000147" target="_blank"><strong>tours</strong></a> and have operated many in association with <strong>Tashkent University</strong>.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1511">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fifteen11" aria-expanded="false" aria-controls="fifteen11">
				          14. What is the visa process and how much time does it take?
				        </button>
				      </h5>
				    </div>
				    <div id="fifteen11" class="collapse" aria-labelledby="1511" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Visa Process is quite simple and one can be sure of getting visa issued by Embassy of Uzbekistan. <a href="https://www.dookinternational.com/visa/uzbekistan" target="_blank"><strong>Tourist Visa</strong></a> generally takes about 08 working days in Normal Process and 05 working   days in express process. In cases of groups, visas are issued in form of group paper visas. However, in cases of individual visas, its sticker visa pasted on passport.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1611">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sixteen11" aria-expanded="false" aria-controls="sixteen11">
				      15. What documents we need to produce to obtain visa?
				        </button>
				      </h5>
				    </div>
				    <div id="sixteen11" class="collapse" aria-labelledby="1611" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>We would need to recent passport size photographs, original passport and a letter stating employment proof.</p>				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1711">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seventeen11" aria-expanded="false" aria-controls="seventeen11">
				         16. I am from Bangladesh / Nepal, can we apply in India for Uzbekistan Tourist Visa?
				        </button>
				      </h5>
				    </div>
				    <div id="seventeen11" class="collapse" aria-labelledby="1711" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Yes, we have had groups from these countries and we can apply for visas on arrival through Ministry of Foreign affairs, Uzbekistan.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1811">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eighteen11" aria-expanded="false" aria-controls="eighteen11">
				         17. How to get business visa for Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="eighteen11" class="collapse" aria-labelledby="1811" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>We can certainly help people intending to have business in Uzbekistan. We would need a letter of invite, 02 recent passport size photographs and original passport.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1911">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nineteen11" aria-expanded="false" aria-controls="nineteen11">
				         18. What are the most popular clubs / Discos in Tashkent City?
				        </button>
				      </h5>
				    </div>
				    <div id="nineteen11" class="collapse" aria-labelledby="1911" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Tashkent is a happening place and plenty of clubs, bars and cafes are all around the city. One can have nice time at SMI, Prince Club, KT komba, FM bar etc.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2011">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twenty11" aria-expanded="false" aria-controls="twenty11">
				         19. What is the currency of Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="twenty11" class="collapse" aria-labelledby="2011" data-parent="#accordionExample">
				      <div class="card-body">
					    <p>Currency of Uzbekistan is UZBEK SOM.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweetyone11" aria-expanded="false" aria-controls="tweetyone11">
				          20. What currency should we carry to Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweetyone11" class="collapse" aria-labelledby="2111" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>You may carry USD or Euro and can get it exchanged at any authorized currency exchange center.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2211">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentytwo11" aria-expanded="false" aria-controls="tweentytwo11">
				        21. Are there any specific rules at the immigration in Tashkent, Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentytwo11" class="collapse" aria-labelledby="2211" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Yes, before crossing the immigration, you are requested to fill up 02 copy of declaration forms and need to disclose your belongings along with the amount of currency you are carrying. It could be any currency. One form will be kept by immigration officer and another will be    returned to you with a stamp on it. You must keep this form with you safely. While departing from Uzbekistan, you are requested to fill up 01 same declaration form and must write down your current belongings and how much currency left with you. Produce both forms (Old Stamped one and New one) to the immigration officer and he will keep both the forms with him.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2311">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentythree11" aria-expanded="false" aria-controls="tweentythree11">22. Is it advisable to travel to Uzbekistan in winter season? 
				        </button>
				      </h5>
				    </div>
				    <div id="tweentythree11" class="collapse" aria-labelledby="2311" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Yes, people say it’s much better than Switzerland in a pocket friendly price.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2411">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfour11" aria-expanded="false" aria-controls="tweentyfour11">
				        23. Is the visa stamped on passport or it’s an EVISA?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfour11" class="collapse" aria-labelledby="2411" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>In case of Individual traveler, visa is stamped on passport and in case of a group traveling, its one paper visa for all travelers.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2511">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfive11" aria-expanded="false" aria-controls="tweentyfive11">
				          24. What is the frequency of Uzbekistan Airways Ex Delhi and Ex Amritsar?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfive11" class="collapse" aria-labelledby="2511" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>From Delhi 06 Flights in a week and from Amritsar 03 Flights in a week.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2611">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentysix11" aria-expanded="false" aria-controls="tweentysix11">
				          25. Do people speak Hindi / English in Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentysix11" class="collapse" aria-labelledby="2611" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>People Speak Uzbek and Russian but the literacy rate in Uzbekistan is quite high and you may find many english speaking people.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2711">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyseven11" aria-expanded="false" aria-controls="tweentyseven11">
				        26. What is there for family groups?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyseven11" class="collapse" aria-labelledby="2711" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>History, culture, shopping &amp; old silk route attracts all genres of tourists. Families can enjoy Aqua Park, bullet train, Ice skating and several water sports in <a href="https://www.dookinternational.com/blog/charvak-reservoir-tashkent/" target="_blank"><strong>Charvak
                      lake</strong></a>. The modern city Tashkent has all to offer.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2811">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight11" aria-expanded="false" aria-controls="tweentyeight11">
				         27. Does the destination attract Honeymooners?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight11" class="collapse" aria-labelledby="2811" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Yes, many honeymoon couples started choosing Uzbekistan as their preferred destination. They spend time in the lap of luxury in Tashkent Hotels, visit peaceful Samarkand and get themselves lost in enchanting roads of Bukhara and Khiva.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="28112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight112" aria-expanded="false" aria-controls="tweentyeight112">
				        28. What other cuisines one must try in Uzbekistan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight112" class="collapse" aria-labelledby="28112" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>You may request local food / Turkish cuisines in your Tashkent Tour Package.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="28113">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight113" aria-expanded="false" aria-controls="tweentyeight113">
				         29. What is there for shopping?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight113" class="collapse" aria-labelledby="28113" data-parent="#accordionExample">
				      <div class="card-body">
				        <p><a href="https://www.dookinternational.com/blog/bazaars-of-tashkent/" target="_blank"><strong>Shopping in Tashkent</strong></a> is a different experience altogether, you may try hands on great quality of dry fruits and they are much cheaper than in India. Uzbek handmade carpets are wonderful option to try on.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="28114">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight114" aria-expanded="false" aria-controls="tweentyeight114">
				         30. Can we get a Hindi speaking guide?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight114" class="collapse" aria-labelledby="28114" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Yes, upon request we can certainly arrange a Hindi speaking guide.</p>
				      </div>
				    </div>
				  </div>
				</div>
            </div>
            <hr>
            <!-- attraction -->
            <div id="Kyrgyzstan" class="tab-pane fade">
                 <div class="col-md-12">
                 	<h6>FAQ of Kyrgyzstan</h6> 
                 	<p>Frequently Asked Questions: Kyrgyzstan Tour, Visa to Bishkek Kyrgyzstan, Travel to Kyrgyzstan, Kyrgyzstan Destinations</p>
                 </div>
               <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne111" aria-expanded="true" aria-controls="collapseOne111">
				           1. Where is Kyrgyzstan situated?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne111" class="collapse show" aria-labelledby="headingOne111" data-parent="#accordionExample">
				      <div class="card-body">
						<p>The country of Kyrgyzstan officially named the Kyrgyz Republic (Kyrgyz Respublikasy), is a landlocked republic in the eastern part of Central Asia. Geographic coordinates are: 41 00 N, 75 00 E.
		                  The neighboring countries of Kyrgyzstan are:</p>
		                <ul class="list-unstyled list-tick">
		                  <li>China</li>
		                  <li>Kazakhstan</li>
		                  <li>Tajikistan</li>
		                  <li>Uzbekistan</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo211">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo211" aria-expanded="false" aria-controls="collapseTwo211">
				         2. Why should one visit Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo211" class="collapse" aria-labelledby="headingTwo211" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li>Kyrgyzstan is a real paradise for people who want to have a rest from modern civilization. The diversity of the landlocked, mountainous country is the essence of Kyrgyzstan and gives the country a unique identity. Along with its attractiveness as one of <strong>Central Asia's
		                      main tourist destination</strong>, it is best known for its mountains, nomads, horses, felt carpets and fermented milk. Besides, <a href="https://www.dookinternational.com/kyrgyzstan-tour-packages" target="_blank"><strong>Kyrgyzstan tour packages</strong></a> include the tour
		                    around the world famous lake - <strong><a href="https://www.dookinternational.com/blog/issyk-kul-lake-a-sublime-place-on-earth/">Issyk Kul</a></strong> - the second largest alpine lake in the world after Lake Titicaca in South America, fringed with beaches and framed by snowy
		                    peaks.
		                  </li>
		                  <li>Bishkek tour packages from India will bring you to an atmosphere of peace and friendship as Kyrgyz people are famous for their great hospitality. Kyrgyz people say, that all guests are sent by God. That is why the main <a
		                      href="https://www.dookinternational.com/blog/kyrgyzstan-a-brief-travel-guide/" target="_blank"><strong>attraction in Kyrgyzstan</strong></a> tour packages are people who saved all their traditions and their unique nomadic culture and
		                    Nature which is virgin and untouched as many centuries ago.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree311">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree311" aria-expanded="false" aria-controls="collapseThree311">
				          3. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree311" class="collapse" aria-labelledby="headingThree311" data-parent="#accordionExample">
				      <div class="card-body">
 						<p>You may reach Delhi and take the Air Manas morning flight from Delhi to Bishkek. There is no other suitable flight connection from India.</p>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="4111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four111" aria-expanded="false" aria-controls="four111">
				         4. What is the best season to visit Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="four111" class="collapse" aria-labelledby="4111" data-parent="#accordionExample">
				      <div class="card-body">
				      <ul class="list-unstyled list-tick">
		                  <li>The most popular destination in Kyrgyzstan is Bishkek. Bishkek tour packages are the most popular with international tourists as Bishkek being the capital of Kyrgyzstan and is the combination of authentic culture with beautiful nature around and modern style of life with fancy
		                    boutiques, restaurants and clubs.
		                  </li>
		                  <li>Surroundings of Bishkek like <a href="https://www.dookinternational.com/blog/ala-archa-national-park-bishkek/" target="_blank"><strong>Natural Park Ala-Archa</strong></a> located in a beautiful Gorge, Famous ski resort Kashka-Suu with a cable car.</li>
		                  <li>Famous Issyk Kul Lake surrounded by snow-picked mountains, with warm and crystal clean water which doesn’t freeze in winter due to it’s high salinity.</li>
		                  <li>For those interested in adventures, Kyrgyzstan tour packages can cover different adventurous activities like hiking, trekking, rafting, parachuting, paragliding etc.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="5111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#five111" aria-expanded="false" aria-controls="five111">
				        5. What are the most interesting places in Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="five111" class="collapse" aria-labelledby="5111" data-parent="#accordionExample">
				      <div class="card-body">
				         <ul class="list-unstyled list-tick">
		                  <li>The most popular destination in Kyrgyzstan is Bishkek. Bishkek tour packages are the most popular with international tourists as Bishkek being the capital of Kyrgyzstan and is the combination of authentic culture with beautiful nature around and modern style of life with fancy
		                    boutiques, restaurants and clubs.
		                  </li>
		                  <li>Surroundings of Bishkek like <a href="https://www.dookinternational.com/blog/ala-archa-national-park-bishkek/" target="_blank"><strong>Natural Park Ala-Archa</strong></a> located in a beautiful Gorge, Famous ski resort Kashka-Suu with a cable car.</li>
		                  <li>Famous Issyk Kul Lake surrounded by snow-picked mountains, with warm and crystal clean water which doesn’t freeze in winter due to it’s high salinity.</li>
		                  <li>For those interested in adventures, Kyrgyzstan tour packages can cover different adventurous activities like hiking, trekking, rafting, parachuting, paragliding etc.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="611">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#six11" aria-expanded="false" aria-controls="six11">
				          6. What kind of tourists would like Kyrgyzstan as a holiday destination?
				        </button>
				      </h5>
				    </div>
				    <div id="six11" class="collapse" aria-labelledby="611" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>Tourists taking Kyrgyzstan tour packages are primarily from the following groups:</p>
			                <ul class="list-unstyled list-tick">
			                  <li>Corporate Incentive Tourist Groups, MICE</li>
			                  <li>Business Tourists</li>
			                  <li>Cultural Tourists - Families and groups wanting to explore Bishkek, which is said to be the ultimate melting pot of cultures with people from over a hundred ethnicities.</li>
			                  <li>Leisure Tourists - Summer time mountain adventure as well as some of the best arenas for winter sports. Many tourists looking for Europe tour package travel and enjoy Kyrgyzstan.</li>
			                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="8111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eight111" aria-expanded="false" aria-controls="eight111">
				        7. How about availability of Indian food?
				        </button>
				      </h5>
				    </div>
				    <div id="eight111" class="collapse" aria-labelledby="811" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Bishkek city, the most <a href="https://www.dookinternational.com/blog/bishkek-a-new-travel-hub/" target="_blank"><strong>popular tourist destination in Kyrgyzstan</strong></a> as well as a business center of the country certainly has a number of authentic Indian restaurants that cater to Indian tourists and local patrons alike. Tourists on Bishkek Tour Package from India always have the option to eat authentic and delicious ndian meals.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="9111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine111" aria-expanded="false" aria-controls="nine111">
				         8. What are the hotel options we have in major cities of Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="nine111" class="collapse" aria-labelledby="9111" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>The major cities of Kyrgyzstan, namely Bishkek and Issyk Kul region are abundant with the best in class 3, 4 and 5 star hotels. In Kyrgyzstan holiday packages tourists have several choices of good hotels in every price range. There is an option of World famous chain of 4* and 5* hotels along with numbers of local luxury 5* and 4* properties.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="10111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ten111" aria-expanded="false" aria-controls="ten111">
				        9. How can we entertain a MICE movement of 200 Pax ? Does the Air Manas has this capacity and what about infrastructure on land?
				        </button>
				      </h5>
				    </div>
				    <div id="ten111" class="collapse" aria-labelledby="10111" data-parent="#accordionExample">
				      <div class="card-body">
				     <p><strong>Option 1</strong>: For <strong>MICE tour package to Kyrgyzstan</strong>, tickets can be booked through Air Manas</p>                <p><strong>Option 2</strong>: Another convenient way to do it is by flying groups of 50-60 daily by Air Astana from Delhi to Almaty and taking them to Bishkek by road (250 Kms, 4 hours including immigration).The event can be organized on the day when all 200 pax are in Bishkek (on day 3 or 4).</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="11111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eleven111" aria-expanded="false" aria-controls="eleven111">
				      10. How about organizing a corporate meet / conference / Award functions etc.
				        </button>
				      </h5>
				    </div>
				    <div id="eleven111" class="collapse" aria-labelledby="11111" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>We organize corporate events for <strong>MICE tour package to Bishkek</strong> of all sizes. Conference halls, banquets or large restaurants are all available to be booked for corporate conferences and award functions with the latest in light and sound options available. (We would need specific and detailed list of requirements in advance for the setup at the event.)</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="12111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twelve111" aria-expanded="false" aria-controls="twelve111">
				       11. Can we organize an educational tour for Students?
				        </button>
				      </h5>
				    </div>
				    <div id="twelve111" class="collapse" aria-labelledby="12111" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>Yes, many international students especially from India study medical sciences in various Institutes and <strong>Universities in Kyrgyzstan</strong>.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="13111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Thirteen111" aria-expanded="false" aria-controls="Thirteen111">
				         12. What is the visa process and how much time does it take?
				        </button>
				      </h5>
				    </div>
				    <div id="Thirteen111" class="collapse" aria-labelledby="13111" data-parent="#accordionExample">
				      <div class="card-body">
				    <p>For Indian tourists the <a href="https://www.dookinternational.com/visa/kyrgyzstan" target="_blank"><strong>visa to Kyrgyzstan</strong></a> is through invitation from a Kyrgyz tour operator and the visa process usually takes about 15 working days.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="14111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#forteen111" aria-expanded="false" aria-controls="forteen111">
				       13. I am from Bangladesh / Nepal, can we apply in India for Kyrgyzstan Tourist Visa?
				         </button>
				      </h5>
				    </div>
				    <div id="forteen111" class="collapse" aria-labelledby="14111" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>Since there are no Kyrgyzstan embassies in Bangladesh and Nepal, citizens of these countries will need to apply for Visa at the Kyrgyzstan embassy in New Delhi.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="15111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fifteen111" aria-expanded="false" aria-controls="fifteen111">
				         14. How to get business visa for Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="fifteen111" class="collapse" aria-labelledby="15111" data-parent="#accordionExample">
				      <div class="card-body">
				       <ul class="list-unstyled list-tick">
		                  <li><strong>Single Entry Business Visas</strong>: A letter with a request to issue a visa addressed to the Consular Section of the Embassy of Kyrgyzstan indicating the purpose of trip, your contact in Kyrgyzstan, the dates of your planned trip and places to be visited.</li>
		                  <li><strong>Double/Triple/Multiple Entry Business Visas</strong>: A copy of the invitation letter from a host organization in Kyrgyzstan is needed. This letter must be submitted by the host organization in Kyrgyzstan to the Department of Consular Services of the Ministry of Foreign
		                    Affairs of the Kyrgyz Republic.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="16111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sixteen111" aria-expanded="false" aria-controls="sixteen111">
				          15. What are the most popular clubs / Discos in Bishkek City?
				        </button>
				      </h5>
				    </div>
				    <div id="sixteen111" class="collapse" aria-labelledby="16111" data-parent="#accordionExample">
				      <div class="card-body">
    				      <p>People of Kyrgyzstan are modern, fun-loving and like to socialize. Hence Bishkek is sprawling with exciting clubs, discos and resto-bars. Retro Metro Club, Canto club, Mansion Bar. Besides, it’s very popular in Bishkek to spend time in Karaoke bars like Live Bar.</p>	
    				  </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="17111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seventeen111" aria-expanded="false" aria-controls="seventeen111">
				         16. What is the currency of Kyrgyzstan?
				         </button>
				      </h5>
				    </div>
				    <div id="seventeen111" class="collapse" aria-labelledby="17111" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The local currency of Kyrgyzstan is Som. Indian Rupee and Kyrgyz som are almost equal.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="18111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eighteen111" aria-expanded="false" aria-controls="eighteen111">
				         17. What currency should we carry to Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="eighteen111" class="collapse" aria-labelledby="1811" data-parent="#accordionExample">
				      <div class="card-body">
				           <p>You may carry US dollars or Euros which can be easily changed to local som at one of the numerous currency exchanges in the cities.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="19111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nineteen111" aria-expanded="false" aria-controls="nineteen111">
				         18. Are there any specific rules at the immigration in Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="nineteen111" class="collapse" aria-labelledby="19111" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>If a tourist wishes to spend more than five days in Kyrgyzstan, he/she will need to apply for registration of stay by visiting the immigration office. This rule is critical and must be adhered to.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="20111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twenty111" aria-expanded="false" aria-controls="twenty111">
				         19. Is it advisable to travel to Kyrgyzstan in winter season?
				        </button>
				      </h5>
				    </div>
				    <div id="twenty111" class="collapse" aria-labelledby="20111" data-parent="#accordionExample">
				      <div class="card-body">
					       <p>The temperatures in Bishkek drop to minus 6 in November and minus 18 degrees in January. But if you like winter adventures, sports or just watching the snow covered landscape, winters in Bishkek would be awesome choice. If you have never experienced such cold weather, it is highly recommended that purchase the suitable winter clothing and <a href="https://www.dookinternational.com/kyrgyzstan/bishkek-winter-3-nights-and-4-days-package/0000115" target="_blank"><strong>make a trip to Bishkek</strong></a>; its guaranteed you'll never regret it, to say the least. Please contact us and we will help you decide the clothing required for your trip.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="21111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweetyone111" aria-expanded="false" aria-controls="tweetyone111">
				         20. Is the visa stamped on passport or its an EVISA?
				        </button>
				      </h5>
				    </div>
				    <div id="tweetyone111" class="collapse" aria-labelledby="21111" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>Visas to Kyrgyzstan are stamped on the passport.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="22111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentytwo111" aria-expanded="false" aria-controls="tweentytwo111">
				        21. What is the frequency of Air Manas Ex Delhi?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentytwo111" class="collapse" aria-labelledby="22111" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Air Manas has three flights a week to Bishkek (FRU) ex-Delhi on Tue, Fri and Sun. Departure time is usually 11:30 a.m.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="23111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentythree111" aria-expanded="false" aria-controls="tweentythree111">
				        	22. Do people speak Hindi / English in Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentythree111" class="collapse" aria-labelledby="23111" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>People of Kyrgyzstan are primarily Kyrgyz and Russian speaking people. Only a section of the modern youth living in the urban places speak English. Even though Hindi movies and songs have been popular in Kyrgyzstan they do not understand or speak Hindi. Hence it is advisable to move around the city only with an English speaking tour guide.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="24111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfour111" aria-expanded="false" aria-controls="tweentyfour111">
				        23. What's in it for families wanting to travel to Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfour111" class="collapse" aria-labelledby="24111" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>For families visiting Kyrgyzstan, there are numerous options for sightseeing and exciting activities around the cities irrespective of their age-group. Apart from the sightseeing, families with kids can spend unforgettable time Panfilov’s Amusement Park (a kind of a local Disney Land) with different attraction ridings or in Aqua Park Ala Too which operates all year around. Besides, there are different shows for kids as well as for adults like balloon shows,fire shows, eagle-hunting shows, tricky horse-riding shows etc. </p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="25111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfive111" aria-expanded="false" aria-controls="tweentyfive111">
				         24. Does the destination attract Honeymooners?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfive111" class="collapse" aria-labelledby="25111" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>Bishkek and places around are considered extremely romantic due to the scenic beauty of the mountains, lakes, flora and fauna and spending evening time at a local nightclub can be memorable for any honeymoon couples. That makes <strong>Bishkek a fantastic honeymoon               destination</strong> similar to Europe but for a fraction of the cost!</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="26111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentysix111" aria-expanded="false" aria-controls="tweentysix111">
				          25. What other cuisines one must try in Kyrgyzstan?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentysix111" class="collapse" aria-labelledby="26111" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>There are numerous local restaurants that serve local Kazakh, Indian, Chinese and Continental dishes. Local restaurants service numerous options for non-vegetarians and some vegetarian dishes are delightful too! Besides that you may always want to taste horse milk and cheese at
                  least once in your lifetime. Horse milk tastes a bit like Indian Chach.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="27111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyseven111" aria-expanded="false" aria-controls="tweentyseven111">
				       26. What is there for the shopaholic?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyseven111" class="collapse" aria-labelledby="27111" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Irrespective of the size of your pockets there's numerous malls and markets that are eye candy for the shopaholic. Whether you're looking for an item that once moved through the Silk Route or an international luxury brand - Bishkek has the best shopping options for you. Amongst
                  conventional markets, <a href="https://www.dookinternational.com/blog/osh-bazaar-bishkek/" target="_blank"><strong>Osh Bazar</strong></a> or <a href="https://www.dookinternational.com/blog/dordoy-bazaar-in-bishkek/" target="_blank"><strong>Dordoi Market</strong></a> is the most
                  popular market and merely a visit can be such a learning experience!</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="28111">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight111" aria-expanded="false" aria-controls="tweentyeight111">
				        27. Can we get a Hindi speaking guide?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight111" class="collapse" aria-labelledby="28111" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>A Hindi-speaking guide can be arranged. <a href="https://www.dookinternational.com/contact-us" target="_blank"><strong>Contact us</strong></a> us well in advance and we can arrange one</p>
				      </div>
				    </div>
				  </div>
				</div>
            </div>
            <hr>
            <div id="Armenia" class="tab-pane fade">
                 <div class="col-md-12">
                 	<h6>FAQ of Armenia</h6> 
                 	<p>Frequently Asked Questions: Armenia Tour, Visa to Armenia, Travel to Armenia, Armenia Destinations</p>
                 </div>
                <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne1112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1112" aria-expanded="true" aria-controls="collapseOne1112">
				           1. Where is Armenia Situated?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne1112" class="collapse show" aria-labelledby="headingOne1112" data-parent="#accordionExample">
				      <div class="card-body">
						 <p>Armenia - officially the Republic of Armenia is located in the South Caucasus region of Eurasia, in Caucasia, on the Armenian Highland.</p>
			                <p><strong>Total area</strong> - 29,743 Sq Kms</p>
			                <p><strong>Independence Year</strong> -1991</p>
			                <p><strong>Capital</strong> - Yerevan</p>
			                <p><strong>Population</strong> - 3 Million</p>
			                <p><strong>Religion</strong> - Christianity</p>
			                <p><strong>Church</strong> - Armenian Apostolic Church (1st c. AD)</p>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo2112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo2112" aria-expanded="false" aria-controls="collapseTwo2112">
				        2. The neighboring countries of Armenia are:
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo2112" class="collapse" aria-labelledby="headingTwo2112" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li>Turkey in the West</li>
		                  <li>Georgia in the North</li>
		                  <li>Azerbaijan in the East</li>
		                  <li>Iran in the South</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree3112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree3112" aria-expanded="false" aria-controls="collapseThree3112">
				          3. Why should one visit Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree3112" class="collapse" aria-labelledby="headingThree3112" data-parent="#accordionExample">
				      <div class="card-body">
 						<ul class="list-unstyled list-tick">
		                  <li>Armenia is a unique and special country. One can compare the <a href="https://www.dookinternational.com/blog/armenia-a-place-for-every-traveller/" target="_blank"><strong>trip to Armenia</strong></a> with the travel back to the roots. Perhaps, it is there, where the history of
		                    the new humanity was continued. According to the biblical legend, Mount Ararat was the destination point where the well-known Noah's Ark was landed after the Great Flood. It might
		                    become a pilgrimage, a chance to touch a relic. Holy places are everywhere here – temples, monasteries, churches – all of them testify on the firmness of Christian faith, and the pride of being the first country in the world which adopted Christianity as an official religion in
		                    301 AD.
		                  </li>
		                  <li>Armenia is particularly valued by people who enjoy culture tourism. They are attracted there by tremendous beauty of nature, exotic atmosphere of mountaineers' way of life, the hospitality of Armenian people and unique ancient culture of Armenians. You will visit the places
		                    where great civilizations prospered, bloody battles raged, historical events of the world importance occurred. Armenian ancient churches and castles, high-mountain lakes and rivers,
		                    majestic rocks, deep canyons hide thousands of stories mysteries…
		                  </li>
		                </ul>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="41112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four1112" aria-expanded="false" aria-controls="four1112">
				         4. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="four1112" class="collapse" aria-labelledby="41112" data-parent="#accordionExample">
				      <div class="card-body">
				      <ul class="list-unstyled list-tick">
		                  <li>Air Arabia has cheap flights from Delhi to Yerevan with a stoppage at Sharjah.</li>
		                  <li>Alternatively, you may take a Fly Dubai flight from Delhi to Yerevan via Dubai.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="51112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#five1112" aria-expanded="false" aria-controls="five1112">
				        5. What is the best season to visit Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="five1112" class="collapse" aria-labelledby="51112" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>Armenian climate is very continental. Summers are dry and sunny lasting from June to mid-September. The summer temperatures range between 22 - 36 degrees centigrade. Short springs usually are from the end of March till mid May; fall from October till end of November and cold snowy winters from mid-December till February. Winter sports enthusiasts can enjoy winter sports, like ice-skating and snowboarding, skiing down the hills of Tsakhkadzor and take the highest cable car there.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="6112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#six112" aria-expanded="false" aria-controls="six112">
				         6. What are the most interesting places in Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="six112" class="collapse" aria-labelledby="6112" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li><strong>Yerevan the capital of Armenia</strong>: There are only few modern cities in the world which are as old as Yerevan. In 2018 the city will celebrate its 2,800th anniversary. This ancient city was founded in 782 BC by the King of Urartu. Ancient Yerevan played a
		                    considerable role in economic and political life of Armenia for many years; it stood on the crossroads of caravan routes, it was the major center of trade. Yerevan city is rich in
		                    masterpieces of the national architecture. Particularly State Academic Theatre of Opera and Ballet, <a href="https://www.dookinternational.com/blog/matenadaran-the-museum-of-ancient-manuscripts-in-yerevan/" target="_blank"><strong>Matenadaran</strong></a>, Concert and Sport
		                    Complex, Haghtanak and Kievyan Bridges are distinguished. In the city center you will also find number of the administrative and governmental buildings as ones in <a href="https://www.dookinternational.com/blog/republic-square-yerevan/" target="_blank"><strong>Republic
		                        Square</strong></a>. The Museum complex (the Picture gallery of Armenia, the Historical Museum, Literature and Arts Museum, the Museum of History of Yerevan, Ministry of Finance and Ministry of Union and Communication, post office, as well as dancing and singing fountains in
		                    the middle of the square. Our <a href="https://www.dookinternational.com/armenia-tour-packages" target="_blank"><strong>Tour Package for Armenia</strong></a> cover most of the tourist places in Yerevan and its surroundings.
		                  </li>
		                  <li><strong>Garni pagan Temple</strong>: Hellenistic <a href="https://www.dookinternational.com/blog/temple-of-garni/" target="_blank"><strong>temple Garni</strong></a> of 1th Century was dedicated to the God of Sun Mitra.</li>
		                  <li><strong>Geghardmonastery or (Airivank)</strong> was founded in the 4th century. <a href="https://www.dookinternational.com/blog/monastery-of-geghard/" target="_blank"><strong>Geghardavank </strong></a> is a holy place, where the spear, by which Jesus Christ was pierced on the
		                    cross by Roman Soldiers was kept. Monastery is designated by UNESCO as World Heritage Site.
		                  </li>
		                  <li><strong>“Tsakhkadzor”</strong> means the Gorge of Flowers. During the Soviet years it was famous all over the country for its mountain-skiing slopes, healthy climate, thick woods and mineral water springs. The world’s highest Cable car is located here. There are a lot of
		                    children's summer camps and rest homes.
		                  </li>
		                  <li><strong>Dilijan</strong> is famous for its magic healing air saturated with the aroma of pines creating very favorable conditions for the people suffering from pulmonary diseases. The climate in Dilijan is mild and sunny with moderately cold winters (average temperature of
		                    January-2C) and cool summers (average temperature in July is 20 С ). The mineral springs rich in carbon dioxide which spew directly from the hillsides add to its popularity. This mineral
		                    water is very similar to Borzhomi from Georgia and Vichy from France.
		                  </li>
		                  <li><strong>Jermuk</strong> is one of the best known resorts in Armenia. The city is popular due to its unique thermal-mineral springs around which the city has grown. “Dzherm” means “warm” in Armenian. The water comes out from the geyser spring (water temperature reaches 60
		                    degrees centigrade). The water is heavily saturated with carbon dioxide and is used for drinking, baths, treatment of intestines, liver, and nervous disorders. Jermuk water is known far
		                    beyond the republic's borders and is similar to the well-known mineral water in Karlovy Vary. Near the springs a number of hotels, sanatoria, and rest houses have been constructed. Jermuk stands at 2,080 m above sea level. Therefore, the view of the snow-peaked mountains and the
		                    gorge which opens from there is extremely beautiful. Summers are cool there, winters are moderate.
		                  </li>
		                  <li><a href="https://www.dookinternational.com/blog/khor-virap/" target="_blank">Khor Virap</a> is a fortified monastery - is the pilgrimage and one of the most worshipped ones in Armenia and the holy site for the Armenian Apostolic Church. The monastery was erected in the 6th -
		                    17th centuries.
		                  </li>
		                  <li><strong>Noravank Monastery</strong> is one of the most spectacular tourist attractions in Armenia. This magic monastery is located in the south of Armenia - in Vayots Dzor province. NORAVANK means NEW MONASTERY in Armenian. Noravank is situated in a narrow gorge made by Amaghu
		                    River and encircled by fantastic red rocks. The beauty of this monastery is appreciated by thousands of visitors not only because of it’s architecture and history, but also for it’s
		                    harmony with the surrounding fabulous nature.
		                  </li>
		                  <li><strong>Etchmiadzin Cathedral</strong> is the Mother church of the Armenian Apostolic Church. It was the built by Armenia's patron saint Gregory the Illuminatorin early fourth century and is considered the oldest cathedral in the world- following the adoption of Christianity as
		                    a state religion by King Tiridates III. It replaced a preexisting temple, symbolizing the conversion from paganism to Christianity.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="81112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eight1112" aria-expanded="false" aria-controls="eight1112">
				         7. How about availability of Indian food?
				        </button>
				      </h5>
				    </div>
				    <div id="eight1112" class="collapse" aria-labelledby="81112" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>There are few Indian restaurants in Yerevan city. North Indian and South Indian Food availability is not an issue in trip to Armenia. Our Packages for Yerevan usually include indian lunch and dinner.</p></div>
		              <div class="faq-box"><h5>8. What kind of tourists would like Armenia as a holiday destination?</h5>
		                <p>Our tour packages for Armenia may relate to the following experiences-</p>
		                <ul class="list-unstyled list-tick">
		                  <li>Classic tours</li>
		                  <li>Cultural tours</li>
		                  <li>Religious tours</li>
		                  <li>Eco tours</li>
		                  <li>Educational tours</li>
		                  <li>Leisure tours including Hiking, tracking, horse riding, paragliding</li>
		                  <li>Jeep Tours to the deep gorges, cruse on the black sea and boat tour on the river</li>
		                  <li>Amusement and entertainment tours</li>
		                  <li>Corporate Incentive tourist groups, MICE</li>
		                  <li>Business tourists</li>
		                  <li>Families</li>
		                  <li>Groups</li>
		                  <li>Honeymooners</li>
		                  <li>Archeologist</li>
		                  <li>Historians</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="91112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine1112" aria-expanded="false" aria-controls="nine1112">
				         9. What are the hotel options we have in major cities of Armenia?
				         </button>
				      </h5>
				    </div>
				    <div id="nine1112" class="collapse" aria-labelledby="91112" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li>There are number of hotel options all over Armenia - starting from guests houses to 3* 4* 5* hotels. From Economy 3* hotels like Nairi, Hrazdan, Silachi Hotel, Mid classhotels like 4*+ Hilton Hotel, Hyatt Place, Royal Plaza, Ani Plaza - to 5* Marriott, Radisson Blue, Royal
		                    Tulip Grand Hotel Yerevan. Mentioned hotels can provide 80 -150 -300 rooms and more.
		                  </li>
		                  <li>There are good hotel options outside of Yerevan in major cities like Tsaghkadzor, Sevan, Dilijan, Gyumri, Jermuk, Goris.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="101112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ten1112" aria-expanded="false" aria-controls="ten1112">
				        10. How can we arrange a MICE movement of 200 Pax?
				        </button>
				      </h5>
				    </div>
				    <div id="ten1112" class="collapse" aria-labelledby="101112" data-parent="#accordionExample">
				      <div class="card-body">
					    <ul class="list-unstyled list-tick">
		                  <li><strong>For MICE tour package to Armenia</strong>, tickets can be booked through Air Arabia, Fly Dubai or by fly combinations through Moscow from where we have number of flights daily.<br>
		                    A more convenient way will be the group of 50-60pax travelling to Yerevan through different flights like from Air Arabia, Fly Dubay or through Moscow all together and organize the event on the day when all members of the group will be in Yerevan.
		                  </li>
		                  <li>Alternatively, we can use our neighbor county Georgian airport, from where the distance is not too far, only ~250km and there are many options with flight combination from India as well.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="111112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eleven1112" aria-expanded="false" aria-controls="eleven1112">
				         11. How about organizing a corporate meet / conference / Award functions etc?
				        </button>
				      </h5>
				    </div>
				    <div id="eleven1112" class="collapse" aria-labelledby="111112" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Upon request we can organize <strong>MICE tour packages</strong>, corporate events for groups of any size. There are good conference and Banquet halls which are available for seminars, corporate presentation, Award Functions celebrations, gala Dinners. The halls can provide all   the required equipment and facilities. All the details for the events are usually checked and set up beforehand. Hence event flow, decoration and equipment requirements need to be shared in advance.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="121112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twelve1112" aria-expanded="false" aria-controls="twelve1112">
				       12. Can we organize an educational tour for Students?
				        </button>
				      </h5>
				    </div>
				    <div id="twelve1112" class="collapse" aria-labelledby="121112" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>Yes we can organize educational tours for student to many higher educational institutions of Yerevan like Agrarian University, Engineering University, University of Architecture and Construction of Armenia, Medical University, and in many other best colleges in Yerevan.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="131112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Thirteen1112" aria-expanded="false" aria-controls="Thirteen1112">
				         13. What is the visa process and how much time does it take?
				        </button>
				      </h5>
				    </div>
				    <div id="Thirteen1112" class="collapse" aria-labelledby="131112" data-parent="#accordionExample">
				      <div class="card-body">
					   <p>For getting <a href="https://www.dookinternational.com/visa/armenia" target="_blank"><strong>tourist visa to Armenia</strong></a> for indians, all the required documents should be applied to the Armenian Embassy in New Delhi at least 15 – 20 Days before departure. Visa fee is 7
	                  USD. Please call us directly for urgent <strong>visa requirements for Armenia</strong>.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="141112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#forteen1112" aria-expanded="false" aria-controls="forteen1112">
				       14. I am from Bangladesh / Nepal, can we apply in India for Visa to Armenia?
				         </button>
				      </h5>
				    </div>
				    <div id="forteen1112" class="collapse" aria-labelledby="141112" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>Since there is no Armenian Embassy in Bangladesh/ Nepal, citizens of these countries will need to apply for Visa at the Armenian Embassy in New Delhi.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="151112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fifteen1112" aria-expanded="false" aria-controls="fifteen1112">
				        15. How to get business visa for Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="fifteen1112" class="collapse" aria-labelledby="151112" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>For getting Business Visa for Armenia, the above mentioned process needs to be followed. The documents should be applied to Armenian Embassy in New Delhi with the dates of planned trip and places to be visited.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="161112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sixteen1112" aria-expanded="false" aria-controls="sixteen1112">
				         16. What are the most popular clubs / Discos in Yerevan?
				        </button>
				      </h5>
				    </div>
				    <div id="sixteen1112" class="collapse" aria-labelledby="161112" data-parent="#accordionExample">
				      <div class="card-body">
    				      <p>There are number of restaurants, clubs, pups, bars in Yerevan among which Jaz Malkhas Clus, JOSE, Yans, Mezzo, Paparazzi Club, Kami Music Club, Opera Club etc, as well as Shangrila Casino are popular.</p>	
    				  </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="171112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seventeen1112" aria-expanded="false" aria-controls="seventeen1112">
				        17. What is the currency of Armenia?
				         </button>
				      </h5>
				    </div>
				    <div id="seventeen1112" class="collapse" aria-labelledby="171112" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The local currency of Armenia is Dram.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="181112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eighteen1112" aria-expanded="false" aria-controls="eighteen1112">
				         18. What currency should we carry to Tbilisi?
				        </button>
				      </h5>
				    </div>
				    <div id="eighteen1112" class="collapse" aria-labelledby="18112" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>US dollars, Euros, Russian Ruble, Great Britain Pounds can be easily changed to local Drams at banks and currency exchange centers in the city. Indian currency is not accepted by the Central bank of Armenia.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="191112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nineteen1112" aria-expanded="false" aria-controls="nineteen1112">
				         19. Are there any specific rules at the immigration in Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="nineteen1112" class="collapse" aria-labelledby="191112" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Armenia has a very liberal immigration policy favoring free movement of persons and business immigration. Immigrants are not required to make large investments, purchase real property, obtain health insurance, and speak Armenian etc. For more information you would need to apply or visit the immigration office.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="201112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twenty1112" aria-expanded="false" aria-controls="twenty1112">
				       20. Is it advisable to travel to Armenia in winter season?
				        </button>
				      </h5>
				    </div>
				    <div id="twenty1112" class="collapse" aria-labelledby="201112" data-parent="#accordionExample">
				      <div class="card-body">
					       <p>skating, snowboarding and skiing down the majestic hills.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="211112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweetyone1112" aria-expanded="false" aria-controls="tweetyone1112">
				         21. Is the visa stamped on passport or its an EVISA?
				        </button>
				      </h5>
				    </div>
				    <div id="tweetyone1112" class="collapse" aria-labelledby="211112" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>Visas to Armenia are stamped on the passport.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="221112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentytwo1112" aria-expanded="false" aria-controls="tweentytwo1112">
				       22. What is the frequency of Air Arabia Ex Delhi?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentytwo1112" class="collapse" aria-labelledby="221112" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>The cheapest flight connection to Yerevan through Sharjah is by Air Arabia each Tuesday and Friday. Arrival is usually at 11:45 a.m.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="231112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentythree1112" aria-expanded="false" aria-controls="tweentythree1112">
				        	23. Do people speak Hindi / English in Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentythree1112" class="collapse" aria-labelledby="231112" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>The majority of Armenian people speak Russian, especial the older generation. Modern youth can understand and speak English and some other European languages. However there are newly organized Hindi language institutions in Yerevan where number of students study Hindi, it is unusual that you come across a Hindi speaking Armenian.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="241112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfour1112" aria-expanded="false" aria-controls="tweentyfour1112">
				       24. What's in it for families wanting to travel to Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfour1112" class="collapse" aria-labelledby="241112" data-parent="#accordionExample">
				      <div class="card-body">
				        <p>There are very good cultural, religious, amusement tour options for Families, including Opera and Ballet theaters, Museums, restaurants etc, who can come and enjoy their stay according to their preferences.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="251112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfive1112" aria-expanded="false" aria-controls="tweentyfive1112">
				        25. Does the destination attract Honeymooners?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfive1112" class="collapse" aria-labelledby="251112" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Yes, the beautiful nature of Armenia with its high mountains, lakes, rapid rivers, deep gorges and alpine meadows is a real romantic place for all the honeymooners to come and feel the breath of the nature, and of course make fantastic photos. As foreigners mention Armenia is a
                  “Museum under the open air”.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="261112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentysix1112" aria-expanded="false" aria-controls="tweentysix1112">
				          26. What other cuisines one must try in Armenia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentysix1112" class="collapse" aria-labelledby="261112" data-parent="#accordionExample">
				      <div class="card-body">
				      <ul class="list-unstyled list-tick">
		                  <li>The Armenian cuisine reaa, Khash, Dolma, Ghaplama, Armenian fish, Arishta, Veg barbeque etc.flects the history and geography where Armenians have lived for centuries. The cuisine also reflects the traditional crops and animals grown and raised in areas populated by Armenians.
		                    The preparation of meat, fish, and vegetable dishes in Armenian kitchen requires stuffing, frothing, and puréeing. Lamb, eggplant, and bread (lavash) are basic features of Armenian
		                    cuisine. There are number of Armenian restaurants where you can find traditional local veg /non veg dishes like Haris.
		                  </li>
		                  <li>Beside of Armenian meals there is European cuisine, Chinese, Georgian, Arabic, Iranian, Indian, also fast foods as Mr. Gyros, KFC, lahmajo etc. Thus, one can find everything according to his taste.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="271112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyseven1112" aria-expanded="false" aria-controls="tweentyseven1112">
				       27. What is there for the shopaholic?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyseven1112" class="collapse" aria-labelledby="271112" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>There are number of Supermarkets in the City center like Sas and City Supermarkets where you can find everything needed from fruits and vegetables to meat, bread, cakes, drinks, there is currency exchange points for 24 h inside, Gum market for dry fruits, spices, Vernisaje
                  handmade souvenir open air market. There are also couple of big malls in Yerevan like Yerevan mall, Rossia, and Dalma Malls where you can find brand shops for kids, Men and Women.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="281112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyeight1112" aria-expanded="false" aria-controls="tweentyeight1112">
				       28. Can we get a Hindi speaking guide?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyeight1112" class="collapse" aria-labelledby="281112" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Yes, Hindi-speaking guides can be made available upon prior request.</p>
				      </div>
				    </div>
				  </div>
				</div>
            </div>
            <hr>
            <div id="Georgia" class="tab-pane fade">
                 <div class="col-md-12">
                 	<h6>FAQ of Georgia</h6> 
                 	<p>Frequently Asked Questions: Georgia Tour, Visa to Georgia, Travel to Georgia, Georgia Destinations</p>
                 </div>
                <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne11123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne11123" aria-expanded="true" aria-controls="collapseOne11123">
				           1. Where is Georgia Situated?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne11123" class="collapse show" aria-labelledby="headingOne11123" data-parent="#accordionExample">
				      <div class="card-body">
						 <p>Georgia is in the Caucasus region of Eurasia and located at the crossroads of Western Asia and Eastern Europe.</p>
			                <ul class="list-unstyled list-tick">
			                  <li><strong>Total area</strong> - 69,700 Sq. Km.</li>
			                  <li><strong>Independence Year</strong> - 1991</li>
			                  <li><strong>Capital and largest city</strong> - Tbilisi</li>
			                  <li><strong>Population</strong> - 3.720.400</li>
			                  <li><strong>Religion</strong> - Eastern Orthodox Christianity</li>
			                  <li><strong>Church</strong> - Georgian Orthodox Church</li>
			                </ul>
			                <strong>The neighboring countries of Georgia are:</strong>
			                <ul class="list-unstyled list-tick">
			                  <li>Russia - from the North</li>
			                  <li>Azerbaijan - from the Southeast</li>
			                  <li>Turkey and Armenia - from the South</li>
			                  <li>Black Sea - from the West</li>
			                </ul>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo2112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo2112" aria-expanded="false" aria-controls="collapseTwo2112">
				       2. Why must one visit Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo2112" class="collapse" aria-labelledby="headingTwo2112" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>Everything in <a href="https://www.dookinternational.com/blog/georgia-a-beautiful-country/" target="_blank"><strong>Georgia</strong></a> speaks about its greatness and cultural wealth of ancient people. Numerous monuments - cult structures of the early Christianity, ancient   churches and monasteries hiding in the Caucasian Mountains are silent witnesses to its rich history. The unique landscapes of this part of the world: high mountains, rapid rivers, green meadows, the turquoise sea … eloquently "speak" about the richness of Georgia's nature. The generosity of the inhabitants of Georgia has become in best expressed by the saying “the Georgian hospitality” which implies a noisy cheerful feast with endless toasts and flow of sweet Georgian wines.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree3112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree3112" aria-expanded="false" aria-controls="collapseThree3112">
				          3. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree3112" class="collapse" aria-labelledby="headingThree3112" data-parent="#accordionExample">
				      <div class="card-body">
 						<p>There are 3 airports in Georgia in Tbilisi, Kutaisi, Batumi (depending on the season) and is well connected.</p>
		                <ul class="list-unstyled list-tick">
		                  <li><strong>Fly Dubai</strong>: Flights from Delhi, Mumbai, Chennai, Hyderabad, Cochin and Ahmadabad to Tbilisi via Dubai.</li>
		                  <li><strong>Air Astana</strong>: From Delhi to Tbilisi via Almaty.</li>
		                  <li><strong>Air Arabia</strong>: Flights from 16 airports in India to Tbilisi via Sharjah (Including- Delhi, Mumbai, Chennai, Hyderabad, Cochin, Ahmedabad , Jaipur, Bangalore, Goa, Trivandrum)</li>
		                </ul>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="411123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four11123" aria-expanded="false" aria-controls="four11123">
				         4. What is the best season to visit Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="four11123" class="collapse" aria-labelledby="411123" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Climate in Georgia is affected by subtropical influences from the west and Mediterranean influences from the east. <a href="https://www.dookinternational.com/about/georgia" target="_blank"><strong>Weather in Georgia</strong></a> gets hot in July and August. Summers are not only hot, but long. The average August temperature throughout the country is 23-26 degrees centigrade. Summer in Georgia is really diverse. West part is hot, stuffy and green. Central part of the country has few forests and often receives dry winds from Azerbaijan. A dry warm wind is frequent in Kutaisi; it causes warming and decrease of humidity. September and October are the best months to <a href="https://www.dookinternational.com/blog/georgia-a-brief-travel-guide/" target="_blank"><strong>travel to Georgia</strong></a>. At this time water in the Black Sea is still warm and in the mountains the weather allows you to ski. Certainly it is the period of pleasant and warm weather and harvest of autumn fruits and vegetables.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="511123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#five11123" aria-expanded="false" aria-controls="five11123">
				        5. What are the most interesting places in Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="five11123" class="collapse" aria-labelledby="511123" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li><strong>Tbilis City</strong>: In 2018 <a href="https://www.dookinternational.com/blog/tbilisi-a-beautiful-eurasian-city/" target="_blank"><strong>Tbilisi</strong></a> will celebrated its 1560th anniversary. It means that the history of this amazing city throws back as far as
		                    the 5th century when the Georgian king Vakhtang Gorgasali ordered to build a city in the center of the fertile valley between mountain ridges. The city's name originated from the word
		                    “tbili” meaning "warm". In fact the plain between Mount Sololaki and Metekhicliff, where the city is situated, is rich in warm sulfuric springs.
		                  </li>
		                  <li> The walks through quiet winding sunny streets, ancient churches with tiled domes, the ruins of old monasteries, traditional Georgian yards with intricate carved porches are simply irresistible. The most visited sites in Tbilisi are Anchiskhati Church, Bridge of Peace, Flea
		                    Market, Metekhi Temple, Mount Mtatsminda, <a href="https://www.dookinternational.com/blog/narikala-fortress/" target="_blank"><strong>Narikala Fortress</strong></a>, Sameba Cathedral,
		                    Sioni Cathedral, St. George Temple (Kashveti), Tbilisi Botanical Garden, Tbilisi Metro, Tbilisi Sulfuric Baths, The Government House, Opera and Ballet Theatre, Rike Park, Russian Drama Theater, etc. Our standard tour package to Tbilisi include most of these attractions.
		                  </li>
		                  <li><strong>Uplistsikhe</strong> was a cult temple city, a large pagan center prior to Christianity introduction in Georgia (the 4th century). Later they observed every possible pagan rituals, and sacrifices. Later Christian churches were built. In the 13th century Uplistsikhe was
		                    destroyed as a result of devastating invasion of Genghis Khan in Georgia. <a href="https://www.dookinternational.com/blog/uplistsikhe/" target="_blank"><strong>Uplistsikhe</strong></a>, this outstanding historical monument in the history of
		                    Georgian culture was revived which is now listed among the historical monuments protected by UNESCO.
		                  </li>
		                  <li><strong>Gori City</strong> is located in the picturesque Kartlia valley. The city is best known also for the birthplace of Joseph Stalin who ruled the Soviet Empire from 1925 to 1953.</li>
		                  <li><strong>Signagi</strong> is a town in Georgia's easternmost region of Kakheti. Although it is one of Georgia's smallest towns, Signagi serves as a popular tourist destination due to its location at the heart of Georgia's wine -growing regions, as well as its picturesque
		                    landscapes, pastel houses and narrow, cobblestone streets. Located on a steep hill, Signagi overlooks the vast Alazani Valley, with the Caucasus Mountains visible at a distance.
		                  </li>
		                  <li><strong>Mtskheta</strong>: Mtskheta is surrounded by majestic mountain tops. It is the ancient capital and one of the oldest cities of Georgia, located 20 kilometres north of Tbilisi at the confluence of the Aragvi and Kura rivers. It is there that streams of Christian pilgrims
		                    and tourists from all over the world flow to. The main attractions there are: one of the most ancient and esteemed temples – Svetitskhoveli Cathedral and ancient Jhvari Monastery.
		                    They both are unique amazing samples of religious architecture of the medieval Caucasus.
		                  </li>
		                  <li><strong>Batumi</strong>: The sunny and modern Batumi personifies all the charm of a southern city and a sea resort with high-class luxury hotels. It is located on the Black Sea coast and is exquisitely framed by exotic subtropical flora. Palm trees, cypresses, magnolias,
		                    oleanders, bamboo trees, laurels, lemon and orange trees delight the eye everywhere. The romantic picture of ships departure from the harbor is better seen from Batumi Quay. Batumi
		                    citizens name this place Seaside Park-Boulevard. It surrounds the city along its sea border for 8 km This is the most popular place for both locals and visitors of the capital. There you can enjoy Ali and Nino monument, Batumi Archaeological Museum, Botanical Garden, Churches of
		                    Batumi, Gonio Fortress etc. A <a href="https://www.dookinternational.com/blog/batumi-a-place-you-must-explore-in-georgia/" target="_blank"><strong>tour to Batumi</strong></a> must
		                    be added to your Tbilisi Travel Package to make it complete.
		                  </li>
		                  <li><strong>Borjomi</strong>: Borjomi is known for its mineral water springs and is situated in the Agura river gorge at the height of 800m above sea level. This is a picturesque place with coniferous forests surrounded by majestic the Caucasian mountains.The Borjomi mineral waters
		                    were mentioned for the first time as early in the XV century. The useful and healing properties of mineral water affects beneficially the digestive system and metabolism in the
		                    body. Today Borjomi mineral water can be bought in shops of over 30 countries of the world.
		                  </li>
		                  <li><strong>Stepantsminda</strong>: Formerly known as Kazbegi, Stepantsminda is a picturesque place 165 kms from Tbilisi. There is a river Thergi running below the town, with snowed peaks of Caucasus rising among which the Mount Kazbek standing out, with its 5033m height. There are
		                    quite a few valley glaciers and forests, both deciduous and coniferous. Fresh mountain air and mineral springs of Stepantsminda are considered to be the main therapy for breathing
		                    or eating disorders.
		                  </li>
		                  <li><strong>Gagrati Cathedral</strong>, another highlight on the UNESCO World Heritage list and a masterpiece in the history of modern and medieval Georgian architecture. It is located in the city of Kutaisi.</li>
		                  <li><strong>Svaneti</strong> is aregion in north-west Georgia, inhabited by an ethic subgroup called Svans. Svaneti is known for its architectural treasures and picturesque landscapes. It's also part of the UNESCO World Heritage list. Svan culture survives most wonderfully in its
		                    songs and dances.
		                  </li>
		                  <li><strong>Ananuri castle</strong> represents multifunctional architectural complex of the late feudal times in Georgia. Built on the right bank of Aragvi River in 16th-17th cc, it was the main seat of Aragvi Eristavi - Dukes dynasty since 13th century. The village Ananuri is
		                    located on the main trade rout leading to the North, to Russia and in past it was part of the Great Silk Road. You may ask us to add above attractions in your Tbilisi tour package.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="6112">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#six112" aria-expanded="false" aria-controls="six112">
				          6. What kind of tourists would like Georgia as a holiday destination?
				        </button>
				      </h5>
				    </div>
				    <div id="six112" class="collapse" aria-labelledby="6112" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>Georgia as a holiday destination offers following experiences:</p>
			                <ul class="list-unstyled list-tick">
			                  <li>Classic tours</li>
			                  <li>Cultural tours</li>
			                  <li>Religious tours</li>
			                  <li>Eco tours</li>
			                  <li>Educational tours</li>
			                  <li>Leisure and Adventure tours - Hiking, tracking, horse riding, paragliding, Jeep Tours to the deep gorges, cruse on the black sea and boat tour on the river</li>
			                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="811123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eight11123" aria-expanded="false" aria-controls="eight11123">
				         7. Georgia also offers Amusement and entertainment tours to:
				        </button>
				      </h5>
				    </div>
				    <div id="eight11123" class="collapse" aria-labelledby="811123" data-parent="#accordionExample">
				      <div class="card-body">
				      <ul class="list-unstyled list-tick">
		                  <li>Corporate Incentive tourist groups, MICE</li>
		                  <li>Business tourists</li>
		                  <li>Families</li>
		                  <li>Groups</li>
		                  <li>Individuals</li>
		                  <li>Honeymooners</li>
		                  <li>Archeologist</li>
		                  <li>Historians</li>
		                </ul>
		            </div>
				    </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="911132">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine11132" aria-expanded="false" aria-controls="nine11132">
				         8. How about availability of Indian food?
				         </button>
				      </h5>
				    </div>
				    <div id="nine11132" class="collapse" aria-labelledby="911132" data-parent="#accordionExample">
				      <div class="card-body">
				             <p>There are few <strong>Indian restaurants in Tbilisi</strong> city offering veg, non veg, Jain and Halal food variety. Indian Northern and Southern Food availability is not an issue. There are also 2 restaurants in Batumi city.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="911123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nine11123" aria-expanded="false" aria-controls="nine11123">
				         9. What are the hotel options we have in major cities of Georgia?
				         </button>
				      </h5>
				    </div>
				    <div id="nine11123" class="collapse" aria-labelledby="911123" data-parent="#accordionExample">
				      <div class="card-body">
				           <p>There are number of hotel options all over Gerogia - starting from guests houses to 3* 4* 5* hotels. From Economy 3* hotels like Astoria, Orion Old Town, Dolabauri, Mid classhotels like 4*+ Astoria TbilisiHotel, Orion Tbilisi, Marriott Courtyard, Mercure, Holiday Inn hotel - to 5* Marriott Tbilisi, Radisson Blue, Sheraton hotel, Biltmore Collection, Ambassador Hotel etc. Mentioned hotels can provide 50 -150 -300 rooms and more. There are good hotel options outside of Yerevan in major cities like Gudauri, Batumi, Borjomi, Kaxety. You may ask us to give you several combinations over cities in your tbilisi tour package.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1011123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ten11123" aria-expanded="false" aria-controls="ten11123">
				        10. How can we entertain a MICE movement of 200 Pax?
				        </button>
				      </h5>
				    </div>
				    <div id="ten11123" class="collapse" aria-labelledby="1011123" data-parent="#accordionExample">
				      <div class="card-body">
					    <p>You may use a combination of Air Astana (ex-Delhi), Fly Dubai and Air Arabia (ports mentioned earlier). All airlines combined can move approx 500 pax in a day, subject to prior reservation well in advance.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1111123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eleven11123" aria-expanded="false" aria-controls="eleven11123">
				        11. How about organizing a corporate meet / conference / Award functions etc.
				        </button>
				      </h5>
				    </div>
				    <div id="eleven11123" class="collapse" aria-labelledby="1111123" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>We organize MICE tour packages, corporate events for different size groups. There are good Conference and Banquet halls which are available for Conferences, Award Functions celebrations, gala Dinners. The halls can provide all the required equipment and facilities. All the details  for the events are usually checked and set up beforehand.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1211123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twelve11123" aria-expanded="false" aria-controls="twelve11123">
				       12. Can we organize an educational tour for Students?
				        </button>
				      </h5>
				    </div>
				    <div id="twelve11123" class="collapse" aria-labelledby="1211123" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>Yes we can organize educational tours for student in many higher educational institutions in Tbilisi. More than 5000 Indian students study in Medical University and in many other reputed colleges in Tbilisi.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1311123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Thirteen11123" aria-expanded="false" aria-controls="Thirteen11123">
				         13. What is the visa process and how much time does it take?
				        </button>
				      </h5>
				    </div>
				    <div id="Thirteen11123" class="collapse" aria-labelledby="1311123" data-parent="#accordionExample">
				      <div class="card-body">
					   <p>The visa policy for Georgia entry is the following: one is E-Visa system, which is done according to the corresponding website and the other by Sticker Visa which is stuck on the passport as a stamp. For getting Sticker visa for Georgia, all the required documents should be     applied to the Georgian Embassy in India-Delhi minimum 15 - 20 Days before departure.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1411123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#forteen11123" aria-expanded="false" aria-controls="forteen11123">
				         14. I am from Bangladesh /Nepal, can we apply in India for Visa to Georgia?
				         </button>
				      </h5>
				    </div>
				    <div id="forteen11123" class="collapse" aria-labelledby="1411123" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>The citizens of Nepal / Bangladesh need to apply to Georgian Embassy in New-Delhi for getting Visa.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1511123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fifteen11123" aria-expanded="false" aria-controls="fifteen11123">
				        15. How to get business visa for Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="fifteen11123" class="collapse" aria-labelledby="1511123" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Georgian business visa is issued for a limited time frame for business trip to undertake business. <strong>Business visa for Georgia</strong> is usually obtained by the Georgian consulate in New Delhi or in the nearest city to your usual residential address. Whenever making an     application for a Georgian business visa, you will be asked to explain the reasons for traveling to Georgia. However, it can not be combined in with your <a href="https://www.dookinternational.com/georgia-tour-packages" target="_blank"><strong>Georgia tour package</strong></a>.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1611123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sixteen11123" aria-expanded="false" aria-controls="sixteen11123">
				         16. What are the most popular clubs / Discos in Tbilisi?
				        </button>
				      </h5>
				    </div>
				    <div id="sixteen11123" class="collapse" aria-labelledby="1611123" data-parent="#accordionExample">
				      <div class="card-body">
    				     <p>Tbilisi is rich with number of clubs, restaurants, discos, pups, bars which are just in the city center nearby Raddison Hotel, in Meidani – Shardeni Street also in Havlabar.</p>	
    				  </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1711123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seventeen11123" aria-expanded="false" aria-controls="seventeen11123">
				        17. What is the currency of Georgia?
				         </button>
				      </h5>
				    </div>
				    <div id="seventeen11123" class="collapse" aria-labelledby="1711123" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>The local currency in Georia is Georgian Lari. The Georgian Lari is also divided into 100 tetri.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1811123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eighteen11123" aria-expanded="false" aria-controls="eighteen11123">
				         18. What currency should we carry to Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="eighteen11123" class="collapse" aria-labelledby="18112" data-parent="#accordionExample">
				      <div class="card-body">
				          <p>In Georgia you can exchange USD - US Dollar , EUR - Euro, GBP- British Pound, INR - Indian Rupee, UAH- The Ukrainian Hryvnia currencies in the banks and exchange points of the city functioning 24 hours.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="1911123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nineteen11123" aria-expanded="false" aria-controls="nineteen11123">
				        19. Are there any specific rules at the immigration in Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="nineteen11123" class="collapse" aria-labelledby="1911123" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Visas are waived for visitors from 94 countries and they are only allowed to stay for 90 days. Among the immigrants, mainly youths from India, came to Georgia to study. The rest came mainly to work as labor migrants. For more information you may visit the Organisation for       Migrations (IOM) in Tbilisi.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2011123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twenty11123" aria-expanded="false" aria-controls="twenty11123">
				          20. Is it advisable to travel to Georgia in winter season?
				        </button>
				      </h5>
				    </div>
				    <div id="twenty11123" class="collapse" aria-labelledby="2011123" data-parent="#accordionExample">
				      <div class="card-body">
					        <p>Yes, one of the most important things about winter in Georgia is the fact that it boasts with great mountain resorts, where you can practice skiing and snowboarding, relax, get rid of stress and feel adrenaline rush, enjoy the snow and the sun, breathe fresh air, drink mulled wine – and all this at very reasonable prices. The best resorts where you can have a rest during winter seasons are in Bakuriani, <a href="https://www.dookinternational.com/blog/gudauri-ski-resort-in-georgia/" target="_blank"><strong>Gudauri</strong></a>, Svaneti, Goderdzi Borjomi, <a href="https://www.dookinternational.com/blog/mount-kazbek/" target="_blank"><strong>Kazbegi Mountain</strong></a>. In Gudauri,two new Dopplemeyer cable cars and 2 ski lifts have been installed and are ready to function since 2016.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2111123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweetyone11123" aria-expanded="false" aria-controls="tweetyone11123">
				        21. Do people speak Hindi / English in Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweetyone11123" class="collapse" aria-labelledby="2111123" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>In Tbilisi people speak English and Russian. There are approx 5000 Indian students studying in Tbilisi universities, and approx. 2000 Indians already became habitants in Georgia. Thus, you will find Hindi Speaking foreigners. Other languages like Iranian, Turkish, Armenian, Hebru etc. is brightly used during high touristic season in Tbilisi, Batumi, etc.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2211123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentytwo11123" aria-expanded="false" aria-controls="tweentytwo11123">
				      22. What's in it for families wanting to travel to Georgia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentytwo11123" class="collapse" aria-labelledby="2211123" data-parent="#accordionExample">
				      <div class="card-body">
				         <p>Georgia is a beautiful country shaped by rugged mountains, rivers, valleys and meadows. This amazing country has unspoiled natural beauty to complement its unique culture. The country is perfect for families for hiking, trekking, and exploring at a leisurely pace. Villages and  towns still dots the countryside while modern cities like Tbilisi, Batumi, and Borjomi are highly captivating and photogenic.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2311123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentythree11123" aria-expanded="false" aria-controls="tweentythree11123">
				        	23. Does the destination attract Honeymooners?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentythree11123" class="collapse" aria-labelledby="2311123" data-parent="#accordionExample">
				      <div class="card-body">
				           <ul class="list-unstyled list-tick">
		                  <li>The Honeymooners will enjoy the fantastic sunrise on the Black Sea, where they can find number of cozy and comfortable hotels. They can have a magnificent rest in Borjomi resort city in esspecialy attractive with is healthy and fresh air Central Park. Borjomi is full of dozens
		                    of healthful institutions, recreation complexes, rest houses. Also we can suggest Tbilisi city – where they can enjoy evening river boat tour as well as having a memorable evening in Traditional Local restaurant tasting fantastic Georgian dishes. Must visit places are also
		                    Mtatsminda museum – catching evening view to the whole city, taking cable cars, walking down the Meidani, Shardeni and Ahmashenebeli streets.
		                  </li>
		                  <li>Lovers of light extreme we can recommend Jeep tours to Kazbegi Mountain.</li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2411123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfour11123" aria-expanded="false" aria-controls="tweentyfour11123">
				       24. What other cuisines one must try in Gerogia?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfour11123" class="collapse" aria-labelledby="2411123" data-parent="#accordionExample">
				      <div class="card-body">
				       <p>Georgian cuisine is probably the most important attraction of the country. Since the traditional Georgian feast is an integral element of culture, Georgian entertainment should match its high level. Georgians have managed to make their cuisine not only magically delicious but also  bright, original, exquisite, unique and unforgettable. Therefore, Georgian cuisine absorbed the best culinary traditions of many people of Transcaucasia, Asia and the Black Sea coast.The easterners use corn flour to cook thick mash - gomi - and eat it instead of bread with meat and vegetable dishes. Eastern Georgians cook mutton, use many animal fats along with the core Georgian meat – beef, while in the Western Georgia they eat much less meat and favor poultry – chicken and turkeys.Beside of Georgian meals there you will find also Eastern, European, cuisines as well as Armenian, Iranian, Indian, Chines, Arabic, Turkish restaurants all over Tbilisi.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2511123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentyfive11123" aria-expanded="false" aria-controls="tweentyfive11123">
				        25. What is there for the shopaholic?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentyfive11123" class="collapse" aria-labelledby="2511123" data-parent="#accordionExample">
				      <div class="card-body">
				     <p>There is East Point ,Tbilisi Mall, Tbilisi Central and Carrefour large shopping centers, as well as number of small souvenir shops in all Tbilisi, also you can find local market like Lilo and others full of meat, bread, cakes, drinks, fruits, vegetables, spices etc.</p>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="2611123">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tweentysix11123" aria-expanded="false" aria-controls="tweentysix11123">
				          26. Can we get a Hindi speaking guide?
				        </button>
				      </h5>
				    </div>
				    <div id="tweentysix11123" class="collapse" aria-labelledby="2611123" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Yes, Hindi-speaking guides are available upon request.</p>
				      </div>
				    </div>
				  </div>
				</div>
            </div>
            <hr>
            <div id="Ukraine" class="tab-pane fade">
                 <div class="col-md-12">
                 	<h6>FAQ of Ukraine</h6> 
                 	<p>Frequently Asked Questions: Ukraine Tour, Visa to Kiev Ukraine, Travel to Ukraine, Ukraine Destinations</p>
                 </div>
                <div class="row mb-4">
                  <div class="accordion" id="accordionExample">
				  <div class="mb-2">
				    <div class="accordion-header" id="headingOne11124">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne11124" aria-expanded="true" aria-controls="collapseOne11124">
				           1. Where is Ukraine situated ?
				        </button>
				      </h5>
				    </div>

				    <div id="collapseOne11124" class="collapse show" aria-labelledby="headingOne11124" data-parent="#accordionExample">
				      <div class="card-body">
						 <p>Ukraine is situated in the eastern part of Europe. It borders on Russia, Belorussia, Poland, Slovakia, Hungary, Romania and Moldova. Ukraine is washed by the Sea of Azov and the Black Sea in the south. The area of Ukraine is more than 603 thousand square kilometers. The most part
                  of its area is flat. There are the Crimean Mountains in the south and the Carpathians in the west.</p>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2">
				    <div class="accordion-header" id="headingTwo21124">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo21124" aria-expanded="false" aria-controls="collapseTwo21124">
				        2. Why should one visit Ukraine?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseTwo21124" class="collapse" aria-labelledby="headingTwo21124" data-parent="#accordionExample">
				      <div class="card-body">
				          <ul class="list-unstyled list-tick">
		                  <li>Ukraine is one of the most beautiful and most visited countries in the Eastern Europe.</li>
		                  <li>Great music, good food, welcoming company and unforgettable impressions are waiting for you in Ukraine.</li>
		                  <li>Traveling is not only about seeing new places; it is also about meeting new people. When visiting Ukraine, you will see how friendly and openhearted Ukrainians are. The majority of young people in big cities <a
		                      href="https://www.dookinternational.com/blog/kyiv-the-beautiful-city-of-ukraine/" target="_blank"><strong>Kiev</strong></a> Kiev, Lviv or Odessa speak English and are ready to help you in any situation.
		                  </li>
		                  <li>Being a big country with a long history, Ukraine will impress you with the diversity of places to visit. Each city has its exceptional look and atmosphere. From the cultural and business center of Kiev to the sunny and friendly Odessa, from the European and cozy Lviv to the
		                    genuine small towns in the Western Ukraine, these Ukrainian cities are waiting to be discovered by you. The diversity of Ukrainian landscape is impressive. The cultural and natural sights inscribed on UNESCO World Heritage List are must-see attractions for all the visitors of
		                    Ukraine.
		                  </li>
		                  <li>When traveling to Ukraine be sure to try such national dishes like borsch and vareniki; you will find it on the menu in every ukrainian restaurant.</li>
		                  <li>Kiev, as a travel destination is considered one of the cheapest among the European capitals. This includes an average price on accommodation, food and drinks, public transport and taxi, admission tickets to the main tourist attractions. Moreover, the rest of Ukrainian cities
		                    are even less expensive. It means that if you choose to come to Ukraine, you will always get more while spend less.
		                  </li>
		                </ul>
				      </div>
				    </div>
				  </div>
				   <div class="mb-2 ">
				    <div class="accordion-header" id="headingThree31124">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree31124" aria-expanded="false" aria-controls="collapseThree31124">
				         3. I am from Bangalore, what will be my flight routing?
				        </button>
				      </h5>
				    </div>
				    <div id="collapseThree31124" class="collapse" aria-labelledby="headingThree31124" data-parent="#accordionExample">
				      <div class="card-body">
 						<p>Yes, you may fly Ex- Bangalore on Air Arabia since Air Arabia flies from 16 ports in India. Even if you are from Delhi, Mumbai, Chennai, Hyderabad, Kochi, Ahmedabad, Jaipur or Nagpur, you may choose to fly on Air Arabia or Fly Dubai without coming down to New Delhi. You may ask us
                  to give you flight from your chosen port in your <a href="https://www.dookinternational.com/ukraine-tour-packages" target="_blank"><strong>Ukraine Tour Package</strong></a>.</p>
				      </div>
				   </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="411124">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four11124" aria-expanded="false" aria-controls="four11124">
				        4. What is the best season to visit Ukraine?
				        </button>
				      </h5>
				    </div>
				    <div id="four11124" class="collapse" aria-labelledby="411124" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>Travel in the Ukraine is possible all year round; however the best times to go are spring, when the blossoms are on the trees, summer with its hot and rainy days and autumn, when the weather is most clement. Winter is obviously cold but the domes and spires of Kiev in particular
                  look fabulous under a layer of snow.</p>
				      </div>
				    </div>
				  </div>
				  <div class="mb-2 ">
				    <div class="accordion-header" id="411125">
				      <h5 class="mb-0">
				        <button class="accordion-button p-2 bg-light text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#four11125" aria-expanded="false" aria-controls="four11125">
				        5. What are the most interesting places in Ukraine?
				        </button>
				      </h5>
				    </div>
				    <div id="four11125" class="collapse" aria-labelledby="411125" data-parent="#accordionExample">
				      <div class="card-body">
				      <p>A fascinating history, rich culture and exquisite natural heritage. Ukraine has much to offer to its visitors. There are many sights in Ukraine to include on your holiday itinerary including monuments, museums, churches, palaces, cemeteries, mountains, rivers, beaches and
                  beautiful landscapes. The most popular and widely visited among them are: <a href="https://www.dookinternational.com/blog/kiev-pechersk-lavra-kiev/" target="_blank"><strong>Kyiv-Pechersk Lavra Kyiv</strong></a>; <a
                    href="https://www.dookinternational.com/blog/pyrohovo-museum-of-folk-architecture-kiev/" target="_blank"><strong>Pyrohovo Museum of Folk Architecture</strong></a> Kyiv, Motherland Statue Kyiv, Mezhyhiria Kyiv, <a
                    href="https://www.dookinternational.com/blog/lvivs-ploshcha-rynok-ukraine/" target="_blank"><strong>Rynok Square Lviv</strong></a>; the Green Pearl of Ukraine - <a href="https://www.dookinternational.com/blog/carpathian-landscapes/" target="_blank"><strong>the Carpathian
                      mountains</strong></a>; The Sea Capital – Odesa.</p>
				      </div>
				    </div>
				  </div>
				</div>

                </div>
            </div>
 
            
       </div>
	     
	    </div>
	</div>
</section>
   @include('frontend.common.testimonial')
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".nav-tabs a").forEach(function(tab) {
        tab.addEventListener("click", function(event) {
            event.preventDefault();
            let target = document.querySelector(this.getAttribute("href"));
            
            if (target) {
                // Remove active class from all tabs
                document.querySelectorAll(".nav-tabs li").forEach(function(li) {
                    li.classList.remove("active");
                });
                
                // Add active class to the clicked tab
                this.parentElement.classList.add("active");

                // Smooth scroll to the section
                window.scrollTo({
                    top: target.offsetTop - 180, // Adjust for navbar height if needed
                    behavior: "smooth"
                });

                // Show the corresponding section
                document.querySelectorAll(".tab-pane").forEach(function(pane) {
                    pane.classList.remove("show", "active");
                });

                target.classList.add("show", "active");
            }
        });
    });
});
</script>
@endsection
