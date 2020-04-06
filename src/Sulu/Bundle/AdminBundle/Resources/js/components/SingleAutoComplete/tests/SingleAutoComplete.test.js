// @flow
import React from 'react';
import {mount, shallow} from 'enzyme';
import SingleAutoComplete from '../SingleAutoComplete';

test('SingleAutoComplete should render', () => {
    const suggestions = [
        {id: 1, name: 'Suggestion 1'},
        {id: 2, name: 'Suggestion 2'},
        {id: 3, name: 'Suggestion 3'},
    ];

    const singleAutoComplete = mount(
        <SingleAutoComplete
            displayProperty="name"
            onChange={jest.fn()}
            onFinish={jest.fn()}
            onSearch={jest.fn()}
            searchProperties={['name']}
            suggestions={suggestions}
            value={{name: 'Test'}}
        />
    );

    expect(singleAutoComplete.render()).toMatchSnapshot();
    expect(singleAutoComplete.find('AutoCompletePopover').render()).toMatchSnapshot();
});

test('SingleAutoComplete should be disabled when in disabled state', () => {
    const suggestions = [
        {name: 'Suggestion 1'},
        {name: 'Suggestion 2'},
        {name: 'Suggestion 3'},
    ];

    const singleAutoComplete = mount(
        <SingleAutoComplete
            disabled={true}
            displayProperty="name"
            onChange={jest.fn()}
            onFinish={jest.fn()}
            onSearch={jest.fn()}
            searchProperties={['name']}
            suggestions={suggestions}
            value={{name: 'Test'}}
        />
    );

    expect(singleAutoComplete.find('input').prop('disabled')).toEqual(true);
});

test('Clicking on a suggestion should call the onChange handler with the value of the selected Suggestion', () => {
    const changeSpy = jest.fn();

    const suggestions = [
        {id: 1, name: 'Suggestion 1'},
        {id: 2, name: 'Suggestion 2'},
        {id: 3, name: 'Suggestion 3'},
    ];

    const singleAutoComplete = mount(
        <SingleAutoComplete
            displayProperty="name"
            onChange={changeSpy}
            onFinish={jest.fn()}
            onSearch={jest.fn()}
            searchProperties={['name']}
            suggestions={suggestions}
            value={{name: 'Test'}}
        />
    );

    singleAutoComplete.find('Suggestion button').at(0).simulate('click');

    expect(changeSpy).toHaveBeenCalledWith(suggestions[0]);
});

test('Should call onChange with undefined if all characters are removed from input', () => {
    const changeSpy = jest.fn();
    const suggestions = [
        {id: 1, name: 'Suggestion 1'},
        {id: 2, name: 'Suggestion 2'},
        {id: 3, name: 'Suggestion 3'},
    ];

    const singleAutoComplete = shallow(
        <SingleAutoComplete
            displayProperty="name"
            onChange={changeSpy}
            onFinish={jest.fn()}
            onSearch={jest.fn()}
            searchProperties={['name']}
            suggestions={suggestions}
            value={{name: 'Test'}}
        />
    );

    expect(singleAutoComplete.find('Input').prop('value')).toEqual('Test');
    singleAutoComplete.find('Input').simulate('change', '');
    expect(changeSpy).toBeCalledWith(undefined);
});

test('Should call the onFinish callback when the Input lost focus', () => {
    const finishSpy = jest.fn();
    const suggestions = [
        {id: 1, name: 'Suggestion 1'},
    ];

    const singleAutoComplete = shallow(
        <SingleAutoComplete
            displayProperty="name"
            onChange={jest.fn()}
            onFinish={finishSpy}
            onSearch={jest.fn()}
            searchProperties={['name']}
            suggestions={suggestions}
            value={{name: 'Test'}}
        />
    );

    singleAutoComplete.find('Input').simulate('blur');

    expect(finishSpy).toBeCalledWith();
});
