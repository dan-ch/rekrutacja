package uwb.licencjat.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Sort;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.validation.BindingResult;
import uwb.licencjat.enums.Gender;
import uwb.licencjat.exception.UserNotFoundException;
import uwb.licencjat.model.Clinic;
import uwb.licencjat.model.User;
import uwb.licencjat.repository.UserRepository;
import uwb.licencjat.security.PasswordGeneratorService;
import uwb.licencjat.validator.UniqueEmailConstraintValidator;
import uwb.licencjat.validator.UniquePeselConstraintValidator;

import java.util.Calendar;
import java.util.List;
import java.util.Optional;

@Service
public class UserService {

    private PasswordEncoder passwordEncoder;
    private UserRepository userRepository;
    private PasswordGeneratorService passwordGeneratorService;

    @Autowired
    public UserService(PasswordEncoder passwordEncoder, UserRepository userRepository,
                       PasswordGeneratorService passwordGeneratorService) {
        this.passwordEncoder = passwordEncoder;
        this.userRepository = userRepository;
        this.passwordGeneratorService = passwordGeneratorService;
    }

    public void saveUser(User user){
        String password = passwordGeneratorService.generatePassword();
        user.setPassword(passwordEncoder.encode(password));
        setDataFromPesel(user);
        userRepository.save(user);
    }

    public boolean existById(long id){
        return userRepository.existsById(id);
    }

    public User findById(long id){
        return checkIsPresent(userRepository.findById(id));
    }

    public void deleteUserById(long id){
        userRepository.deleteById(id);
    }

    public List<User> getAllByValueSorted(String value, String sortBy, String sortDirection){
        Sort sort;
        if(sortDirection.equals("desc"))
            sort = Sort.by(Sort.Direction.DESC, sortBy);
        else
            sort = Sort.by(Sort.Direction.ASC, sortBy);
        return userRepository.findAllByNameLastnameEmailPeselContaining(value, value, value, value, sort);
    }

    private void setDataFromPesel(User user){
        String dateFromUser = user.getPesel().substring(0,6);
        int genderFromUser = Integer.parseInt(user.getPesel().substring(9,10));
        System.out.println(genderFromUser);
        Calendar birthDate = Calendar.getInstance();
        int month = Integer.parseInt(dateFromUser.substring(2,4));
        if(month < 20)
            birthDate.set(Integer.parseInt("19"+dateFromUser.substring(0,2)), month,
                    Integer.parseInt(dateFromUser.substring(4,6)));
        else
            birthDate.set(Integer.parseInt("20"+dateFromUser.substring(0,2)), month-20,
                    Integer.parseInt(dateFromUser.substring(4,6)));
        user.setBirthDate(birthDate);
        if (genderFromUser % 2 == 0) {
            user.setGender(Gender.Kobieta);
        } else {
            user.setGender(Gender.Mężczyzna);
        }
    }

    private User checkIsPresent(Optional<User> userOptional){
        if(userOptional.isPresent()){
            return userOptional.get();
        }else{
            throw new UserNotFoundException();
        }
    }

    public void checkEmailPasswordUnique(long id, User user, BindingResult result){
        Optional<User> userFromDb = userRepository.findByEmail(user.getEmail());
        if(userFromDb.isPresent()){
            if(userFromDb.get().getId()!=id)
                result.rejectValue("email","UniqueEmail", "Podany email jest juz w bazie");
        }
        userFromDb = userRepository.findByPesel(user.getPesel());
        if(userFromDb.isPresent()){
            if(userFromDb.get().getId()!=id)
                result.rejectValue("pesel","UniquePesel", "Podany PESEL jest juz w bazie");
        }
    }

    public void checkEmailPasswordUnique(User user, BindingResult result){
        if(userRepository.existsByEmailIgnoreCase(user.getEmail()))
            result.rejectValue("email","UniqueEmail", "Podany email jest juz w bazie");
        if(userRepository.existsByPeselIgnoreCase(user.getPesel()))
            result.rejectValue("pesel","UniquePesel", "Podany PESEL jest juz w bazie");
        }
}

