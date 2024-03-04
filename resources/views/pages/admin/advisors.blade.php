@php

    $advisors = \App\Models\Advisor::all();
    $results = \App\Models\Result::paginate(6);

    $advisor_id = request()->advisor_id;
    if (!is_numeric($advisor_id)) {
        $advisor_id = 'null';
    }
    
@endphp
<x-template nav="advisors" title="Admin - Advisors Manager">
    <div ng-controller="AdvisorController" ng-init="init()">
        <div class="md:flex justify-between items-stretch max-h-full min-h-full overflow-hidden">
            <div ng-cloak ng-show="!advisor || winLarge"
                class="md:w-[380px] bg-slate-50 border-r dark:border-black bg-zinc-300/25">
                <div>
                    <form
                        class="flex items-center justify-between gap-2 w-full flex-wrap  p-5 border-b dark:border-white/25 ">

                        <div class="flex-1">
                            <input type="search"
                                class="input w-full bg-white dark:bg-zinc-600 !py-3 !px-4 !rounded-full"
                                placeholder="Enter Advisor's Name" />
                        </div>

                    </form>

                    <div class="flex flex-col h-full  rounded-md p-4">
                        <div class="scroller">
                            <table class="border-collapse" style="border-collapse: collapse";>
                                <tbody>
                                    @foreach ($advisors as $advisor)
                                        <tr advisor_id="{{ $advisor->id }}" ng-click="show({{ $advisor->id }})"
                                            class="hover:bg-green-100 cursor-pointer gap-3 border-b border-slate-200 dark:hover:bg-zinc-600/70 rounded-md last:border-transparent items-center">
                                            <td align="center" class="py-4 pl-3">
                                                <img src="{{ $advisor->picture() }}" alt="PIC"
                                                    class="w-10 h-10 rounded-full object-cover" />
                                            </td>
                                            <td>
                                                {{ $advisor->user->name }}
                                            </td>
                                            <td class="text-center">
                                                ID<br>
                                                ADV-000{{ $advisor->user->unique_id }}
                                            </td>
                                            <td class="text-center pr-3">
                                                Students<br>
                                                1000
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>





            <div ng-cloak ng-cloak ng-show="advisor || winLarge" class="md:flex-1 md:bg-green-50/10">
                <div class="scroller">
                    <div class="flex justify-between items-center">
                        <x-navigate>
                            <a class="btn-white top-0 sticky" href="/admin/advisor/add">Add Advisor</a>
                        </x-navigate>

                    </div>


                    <div ng-show="advisor">

                        <div class="flex flex-col lg:m-5 bg-white dark:bg-inherit rounded-md p-8">
                            <div
                                class="bg-slate-50/10 h-36 flex flex-col lg:flex-row text-center justify-center gap-3 items-center lg:text-left lg:justify-start p-4 relative border-b-4">
                                <div>
                                    <p class="text-2xl lg:text-3xl font-bold mb-3" ng-bind="advisor.user.name"></p>
                                    <p class="font-bold" ng-bind="advisor.staff_id"></p>
                                </div>

                                <img src="{% advisor.image %}"
                                    class="w-28 h-28 object-cover rounded-full absolute right-10 -bottom-[2.8rem] border-4" />
                            </div>
                            <div class="flex-1 bg-white dark:bg-zinc-800">
                                <div>
                                    <div class="p-4 my-2">
                                        <div class="mb-1 font-semibold text-slate-900 dark:text-slate-200">Basic
                                            Information</div>
                                        <div class="lg:flex flex-wrap gap-3">
                                            <div>
                                                Phone
                                                <div class="font-semibold" ng-bind="advisor.phone">
                                                </div>
                                            </div>


                                            <div>
                                                Email
                                                <div class="font-semibold" ng-bind="advisor.user.email">
                                                </div>
                                            </div>





                                            <div>
                                                Address
                                                <div class="font-semibold" ng-bind="advisor.address">

                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <div class="p-4 my-2">
                                        <div class="mb-1 font-semibold text-slate-900 dark:text-slate-200">Class
                                            Information</div>
                                        <div class="lg:flex gap-5">
                                            <div class="lg:flex lg:flex-col">
                                                <span>Class</span>
                                                <span class="font-semibold" ng-bind="advisor.academic_set.name"></span>
                                            </div>

                                            <div class="lg:flex lg:flex-col">
                                                <span>Level</span>
                                                <span class="font-semibold" ng-bind="500"></span>
                                            </div>

                                            <div class="lg:flex lg:flex-col">
                                                <span>No of Students</span>
                                                <span class="font-semibold" ng-bind="advisor.studentsCount"></span>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="p-4 my-2">
                                        <div class="flex items-center space-x-2 text-base">
                                            <h4 class="font-semibold text-slate-900 dark:text-slate-200">Students</h4>
                                            <span
                                                class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{% advisor.studentsCount %}</span>
                                        </div>
                                        <div class="mt-3 flex -space-x-2 overflow-hidden p-2">
                                            <img ng-repeat="student in advisor.students track by student.id"
                                                class="inline-block h-12 w-12 object-cover rounded-full ring-2 ring-white"
                                                src="{% student.picture %}" alt="{% student.user.name %}" />
                                        </div>
                                        <div class="mt-3 text-sm font-medium" ng-show="(advisor.studentsCount - 3) > 0">
                                            <a href="#" class="text-blue-500">+ {% advisor.studentsCount-3 %}
                                                others</a>
                                        </div>

                                    </div>



                                </div>

                                <div class="flex justify-end px-4 pb-3">
                                    <a  href="/admin/advisor/edit?advisor_id={%advisor.id%}" class="btn-primary">
                                        Edit Advisor Details
                                    </a>
                                </div>

                            </div>

                        </div>


                    </div>

                    <div ng-show="!advisor" view-student-skeleton>
                    </div>

                   
                </div>

            </div>
        </div>
    </div>

</x-template>
