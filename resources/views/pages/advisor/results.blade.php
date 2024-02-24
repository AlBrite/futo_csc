<x-template title="Advisor - Results" nav="results">
    <div class="p-4">
        <style>
            @media screen and (max-width: 367.5px ) {
                #search-label {
                    top: .3rem
                }
            }
            
        </style>
        
        <div id="advisor-results" class="flex items-center text-sm font-semibold gap-4 text-body-200">
            <a href="/advisor/results" class="active">View Results</a>
            <a href="/advisor/upload-result">Upload Results</a>
            <a href="/advisor/cgpa-summary-result">CGPA Summary Result</a>
        </div>

        <div class="w-full mt-4" x-data="{semester:false, course: false, session: false}">
            <form action="/display_results" method="get" class="flex items-center gap-x-2 w-full relative flex-wrap mb-4">
                @csrf
                <label for="student-search" class="text-body-200 absolute top-3 left-1" id="search-label">
                    <span class="material-symbols-rounded">search</span>
                </label>
                <input type="search" name="studentSearch" id="student-search" placeholder="Enter Name or Reg Number" class="input">
                
                <button type="submit"
                    class="btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                    Submit
                </button>
            </form>

            <form action="/display_results" method="get" class="flex items-end gap-x-4" id="result-options-form print:hidden">
            
                <div class="">
                    <label for="session">Session</label>
                    
                    <select x-on:change="session=$event.target.value" name="session" id="session" class="w-full input">
                        <option value="" class>Select session</option>
                        @php 
                            $start_year = 2018;
                            $this_year = date("Y");
                            $diff = $this_year - $start_year;

                            $year = $start_year;
                            

                            for($n = $start_year; $n < $this_year; $n++) {
                                $end = $year+1;
                                $session = "{$year}/{$end}";
                                echo "<option value='$session'>$session</option>";
                                $year++;
                            }
                        @endphp
                    </select>
                </div>

                <div class="">
                    <label for="semester">Semester</label>
                    <select x-on:change="semester=$event.target.value" :disabled="!session" name="semester" id="semester" class="input" disabled>
                        <option value="">Select semester</option>
                        <option value="harmattan">Harmattan</option>
                        <option value="rain">Rain</option>
                    </select>
                </div>

                <div class="">
                    <label for="course">Course</label>
                    <select x-on:change="course=$el.value"  :disabled="!semester" name="course" id="course" class="input" disabled>
                        <option value="">Select course</option>
                        <option value="all">All courses</option>
                        <option value="1">MTH 101</option>
                    </select>
                </div>

                <button :disabled="!course" type="submit" disabled class="btn-sm btn-primary !m-0">
                    View Result
                </button>
            </form>
        </div>
    </div>

    
</x-template>