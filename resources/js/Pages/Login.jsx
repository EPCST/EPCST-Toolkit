import React, {useState} from "react";
import {Link} from "@inertiajs/react";
import {route} from "ziggy-js";

const Login = () => {
  const [progress, setProgress] = useState(0);
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);

  let headerSteps = [
    {title: 'Data Synchronization', description: 'Use the credentials you use for the grade book system.'},
    {title: 'User Information', description: 'Some basic information for settings up the system to suit you.'},
    {title: 'Other Settings', description: 'Customization and other advanced settings'}
  ];

  function nextStep() {
    if(step + 1 === 4) {
      console.log('here');
    }

    if(step + 1 > 3) {
      return;
    }

    setStep(step + 1);
    setProgress(((step - 1) / 2) * 100);
  }

  function prevStep() {
    if(step - 1 < 1) {
      return;
    }

    setStep(step - 1);
    setProgress(((step - 1) / 2) * 100)
  }

  function connectToGradebook(event) {
    event.preventDefault();
  }

  function handleFormInput(event) {

  }

  function determineFormContent() {
    if(step === 1) {
      return (
        <>
          <div className="grid gap-6 mb-6 md:grid-cols-2">
            <div>
              <label htmlFor="apiUsername" className="block mb-2 text-sm font-medium text-gray-90">Username</label>
              <input onChange={handleFormInput} type="text" id="apiUsername" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="epcst_[lastname]" required/>
            </div>
            <div>
              <label htmlFor="apiPassword" className="block mb-2 text-sm font-medium text-gray-90">Password</label>
              <input onChange={handleFormInput} type="password" id="apiPassword" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus focus:border-blue-500 block w-full p-2.5" placeholder="•••••••••" required/>
            </div>
            <input type="hidden" id="apiToken" onChange={handleFormInput}/>
          </div>
          <div className="flex justify-center">
            <button onClick={connectToGradebook} disabled={loading} className={`${loading && 'opacity-50 cursor-not-allowed'} bg-blue-600 hover:bg-blue-700 py-2 px-4 text-white rounded-md text-sm min-w-64`}>Connect</button>
          </div>
        </>
      )
    }
    else if(step === 2) {
      return (
        <>
          <div className="grid gap-6 mb-6 grid-cols-3">
            <div>
              <label htmlFor="first_name" className="block mb-2 text-sm font-medium text-gray-90">First name</label>
              <input onChange={handleFormInput} type="text" id="first_name" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="John" required/>
            </div>
            <div>
              <label htmlFor="middle_name" className="block mb-2 text-sm font-medium text-gray-90">Middle Name</label>
              <input onChange={handleFormInput} type="text" id="middle_name" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Goe" required/>
            </div>
            <div>
              <label htmlFor="last_name" className="block mb-2 text-sm font-medium text-gray-90">Last name</label>
              <input onChange={handleFormInput} type="text" id="last_name" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus focus:border-blue-500 block w-full p-2.5" placeholder="Doe" required/>
            </div>
          </div>
          <div className="grid gap-6 mb-6 md:grid-cols-2">
            <div>
              <label htmlFor="department" className="block mb-2 text-sm font-medium text-gray-90">Department</label>
              <select id="department" onChange={handleFormInput} className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                <option disabled selected value="">Choose a department</option>
                <option value="cs">Computer Studies</option>
                <option value="engineering">Engineering</option>
                <option value="gened">General Education</option>
                <option value="hrm">Hotel and Restaurant Management</option>
              </select>
            </div>
            <div>
              <label htmlFor="email" className="block mb-2 text-sm font-medium text-gray-90">Email address</label>
              <input onChange={handleFormInput} type="email" id="email" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="teacher@epcst.edu.ph" required/>
            </div>
          </div>
        </>
      )
    }
    else if(step === 3) {
      return (
        <div className="grid gap-6 mb-6 md:grid-cols-2">
          <div>
            <label htmlFor="idSystemIP" className="block mb-2 text-sm font-medium text-gray-90">IP Address of ID System</label>
            <input onChange={handleFormInput} type="text" id="idSystemIP" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="" required/>
          </div>
        </div>
      )
    }
  }

  return (
    <div className="flex justify-center p-8 min-h-screen h-full w-screen bg-gray-950 text-white">
      <div className="lg:w-2/3 w-full">
        <h1 className="mt-8 text-xl md:text-3xl text-center w-full">System Setup</h1>

        <div className="flex justify-between mt-8 text-white relative">
          <div
            className={`${progress === 50 ? 'w-[50%]' : progress === 100 ? 'w-[100%]' : 'w-[0%]'} transition-all delay-100 ease-in-out border border-green-400 absolute`}
            style={{top: "calc(3em / 2)"}}></div>
          <div
            className={`${step === 1 ? 'bg-yellow-400' : 'bg-green-400'} transition-all delay-200 ease-in-out p-4 w-12 h-12 flex justify-center items-center rounded-full z-10`}>1
          </div>
          <div
            className={`${step === 2 ? 'bg-yellow-400' : step < 2 ? 'bg-blue-400' : 'bg-green-400'} transition-all delay-200 ease-in-out p-4 w-12 h-12 flex justify-center items-center rounded-full z-10`}>2
          </div>
          <div
            className={`${step === 3 ? 'bg-yellow-400' : step < 3 ? 'bg-blue-400' : 'bg-green-400'} transition-all delay-200 ease-in-out p-4 w-12 h-12 flex justify-center items-center rounded-full z-10`}>3
          </div>
        </div>

        <form className="h-2/3 bg-gray-700 text-white rounded-lg mt-8">
          <div className="bg-gray-800 rounded-t-lg p-8">
            <h1 className="font-bold text-2xl">{headerSteps[step - 1].title}</h1>
            <p className="font-light">{headerSteps[step - 1].description}</p>
          </div>

          <div className="m-4">
            <div className="rounded-md p-4">
              {determineFormContent()}
            </div>
          </div>
        </form>
        <div className={`flex justify-between ${step === 1 ? 'hidden' : ''}`}>
          <button type="button" className={`border-2 border-gray-400 hover:border-gray-600 py-2 px-4 text-white rounded-md text-sm min-w-64 ${step <= 2 ? 'invisible' : ''}`} onClick={prevStep}>Previous</button>
          <button type="button" className={`${step === 3 ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'} py-2 px-4 text-white rounded-md text-sm min-w-64`} onClick={nextStep}>{step === 3 ? 'Finish' : 'Next'}</button>
        </div>
      </div>
  </div>
  );
};

export default Login;
